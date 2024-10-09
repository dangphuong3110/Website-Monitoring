<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

class TesseractOCRController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                        $results[] = 'Không tìm thấy văn bản trong hình ảnh.';
                    }
                    else {
                        foreach ($result as $r) {
                            $results[] = preg_replace('/[^a-zA-Z0-9ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ .,\-\/|:;%]/u', '', $r);
                        }
                    }
                } catch (\Exception $exception) {
                    $results[] = 'Không tìm thấy văn bản trong hình ảnh.';
                    if (count($images) - $count > - 1) {
                        continue;
                    }
                    return $results;
                }
                $oldImagePath = public_path('assets/image/product/dashboard.blade.php');
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        return $results;
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
            $res = $client->post('https://api.withoutbg.com/v1.0/image-without-background', [
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
                    'X-Api-Key' => 'b28c8e70-1c8b-475c-88ed-9f549f00091f'
                ]
            ]);
            $fp = fopen("assets/image/removebg/no-bg-".$randomNumber.".png", "wb");
            fwrite($fp, $res->getBody());
            fclose($fp);

            return $randomNumber;
        }

        return 0;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
