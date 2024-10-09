<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <title>Document</title>
    </head>
    <body>
        <div style="background:#ffffff;border-radius:6px;padding:25px;border:1px solid #efefef;font-size:14px;margin-bottom:25px">
            <div>
                <p style="line-height:20px">Hello {{ $incidents[0]->username }},</p>
                <p style="line-height:20px"><strong>We just detected an incident on your monitor. Your service is currently down.</strong></p>
            </div>
            <table class="table" style="padding:30px 20px 20px 20px;background-color:#FEF1EF;border:2px solid #F8B4A8;border-radius:6px;margin-top:25px;line-height:16px">
                <thead>
                <tr>
                    <th scope="col">URL</th>
                    <th></th>
                    <th scope="col">Root cause</th>
                    <th></th>
                    <th scope="col">Started at</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="5">
                        <hr style="border:1px solid #dedede;border-bottom:0;margin:1px 0 10px 0">
                    </td>
                </tr>
                @foreach($incidents as $incident)
                    <tr>
                        <td>
                            <a href="{{ $incident->url }}" style="color:#131a26!important;text-decoration:none!important" target="_blank" data-saferedirecturl="https://www.google.com/url?q={{ $incident->url }}&amp;source=gmail&amp;ust=1702975998121000&amp;usg=AOvVaw0zi3ATS2n8JZHAfOXqkePh">{{ $incident->url }}</a>
                        </td>
                        <td>||</td>
                        <td>
                            @if($incident->status == 'Ongoing')
                                The website is not accessible
                            @else
                                Connection Timeout
                            @endif
                        </td>
                        <td>||</td>
                        <td>{{ $incident->started_at }}</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <hr style="border:1px solid #dedede;border-bottom:0;margin:10px 0">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div style="text-align:center;padding:15px 0">
                <table>
                    <tbody>
                    <tr>
                        <td>
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                    <td align="left" style="border-radius:25px;background-color:#3bd671;text-align:center">
                                        <a href="{{ config('app.url') }}/website-monitoring/dashboard" style="font-size:16px;color:#ffffff!important;font-weight:bold;text-decoration:none;border-radius:25px;padding:12px 25px;border:1px solid #3bd671;display:inline-block" target="_blank">View incident details</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>

{{--        <div style="padding:20px;background-color:#f9f9f9;border-radius:6px;margin-top:25px;line-height:16px">--}}
{{--    --}}
{{--            <div style="color:#687790;font-size:12px">Checked URL</div>--}}
{{--            <h2 style="font-size:14px;margin-bottom:5px;margin-top:3px;line-height:16px"><code>--}}
{{--                    <a href="{{ $incident['url'] }}" style="color:#131a26!important;text-decoration:none!important" target="_blank" data-saferedirecturl="https://www.google.com/url?q={{ $incident['url'] }}&amp;source=gmail&amp;ust=1702975998121000&amp;usg=AOvVaw0zi3ATS2n8JZHAfOXqkePh">{{ $incident['url'] }}</a></code></h2>--}}
{{--            <hr style="border:1px solid #dedede;border-bottom:0;margin:10px 0">--}}

{{--            <div style="color:#687790;font-size:12px">Root cause</div>--}}
{{--            <h2 style="font-size:14px;margin-bottom:5px;margin-top:3px">--}}
{{--                @if($incident['status'] == 'Ongoing')--}}
{{--                    The website is not accessible--}}
{{--                @else--}}
{{--                    Connection Timeout--}}
{{--                @endif--}}
{{--            </h2>--}}
{{--            <hr style="border:1px solid #dedede;border-bottom:0;margin:10px 0">--}}

{{--            <div style="font-size:12px;color:#687790">Incident started at</div>--}}
{{--            <h2 style="font-size:14px;margin-bottom:5px;margin-top:3px">{{ $incident['started_at'] }}</h2>--}}
{{--            <hr style="border:1px solid #dedede;border-bottom:0;margin:10px 0">--}}

{{--            <div style="font-size:12px;color:#687790">Tab</div>--}}
{{--            <h2 style="font-size:14px;margin-bottom:5px;margin-top:3px">{{ $incident['tab_name'] }}</h2>--}}
{{--            <hr style="border:1px solid #dedede;border-bottom:0;margin:10px 0">--}}

{{--            <div style="text-align:center;padding:15px 0">--}}
{{--                <table>--}}
{{--                    <tbody>--}}
{{--                    <tr>--}}
{{--                        <td>--}}
{{--                            <table border="0" cellspacing="0" cellpadding="0">--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td align="left" style="border-radius:25px;background-color:#3bd671;text-align:center">--}}
{{--                                        <a href="{{ config('app.url') }}/website-monitoring/dashboard?tab={{ $incident['tab_id'] }}" style="font-size:16px;color:#ffffff!important;font-weight:bold;text-decoration:none;border-radius:25px;padding:12px 25px;border:1px solid #3bd671;display:inline-block" target="_blank">View incident details</a>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
