<?php

namespace App\Http\Controllers;

use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;
use GuzzleHttp\Client;

class TesseractOCRController extends Controller
{
    public function index()
    {
        $results = [''];
        return view('ocr.convert-img-to-text', compact('results'));
    }

    public function processImage(Request $request)
    {
        $results = [''];
        $language = $request->input('language');
        if ($request->hasFile('img')) {
            $images = $request->file('img');
            $count = count($images) > 1 ? 1 : null;
            foreach ($images as $image) {
                if (!$image) {
                    continue;
                }
                $extension = $image->getClientOriginalExtension();
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
                if (!in_array($extension, $allowedExtensions)) {
                    continue;
                }
                if ($count) {
                    $results[] = '-------Ảnh '.$count.': '.$image->getClientOriginalName().'-------';
                    $count++;
                }
                $imageName = 'dashboard.blade.php.jpg';
                try {
                    $image->move(public_path('assets/image/product'), $imageName);
                    $imagePath = public_path('assets/image/product/' . $imageName);
                    $text = new TesseractOCR($imagePath);
                    if ($language == 1) {
                        $text->lang('vie');
                    } else if ($language == 2) {
                        $text->lang('eng');
                    }
                    $result = explode("\n", $text->run());
                    if (count($result) === 1 && $result[0] === "") {
                        $results[] = 'Không tìm thấy văn bản trong hình ảnh';
                    }
                    else {
                        foreach ($result as $r) {
                            $results[] = preg_replace('/[^a-zA-Z0-9ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ .,\-\/|:;%]/u', '', $r);
                        }
                    }
                } catch (\Exception $exception) {
                    $results[] = 'Không tìm thấy văn bản trong hình ảnh';
                    if (count($images) - $count > -1) {
                        continue;
                    }
                    return view('ocr.convert-img-to-text', compact('results'));
                }
                $oldImagePath = public_path('assets/image/product/dashboard.blade.php');
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        return view('ocr.convert-img-to-text', compact('results'))->with('success', 'Image has been scanned successfully.');
    }

    public function test()
    {
        $randomNumber = 0;
        return view('ocr.remove-background-img', compact('randomNumber'));
    }

    public function removeBg (Request $request)
    {
        $oldImagePath = public_path('assets/image/removebg/file.jpg');
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }

        $image = $request->file('img-test');

        if ($image) {
            $randomNumber = rand(111111,999999);
            $image->move(public_path('assets/image/removebg'), 'file-'.$randomNumber.'.jpg');

            $client = new Client([
                'verify' => false,
            ]);
            $res = $client->post('https://api.remove.bg/v1.0/removebg', [
                RequestOptions::MULTIPART => [
                    [
                        'name'     => 'file',
                        'contents' => fopen(public_path('assets/image/removebg/file-'.$randomNumber.'.jpg'), 'r')
                    ],
                    [
                        'name'     => 'size',
                        'contents' => 'auto'
                    ]
                ],
                RequestOptions::HEADERS => [
                    'X-Api-Key' => 'C7tXrwhk473zouXdUve2SrUK'
                ]
            ]);
            $fp = fopen("assets/image/removebg/no-bg-".$randomNumber.".png", "wb");
            fwrite($fp, $res->getBody());
            fclose($fp);

            return view('ocr.test')->with('success', 'Xóa nền ảnh thành công.')->with('randomNumber', $randomNumber);
        }

        return view('ocr.test')->with('failure', 'Không tìm thấy file tải lên.');
    }
}
