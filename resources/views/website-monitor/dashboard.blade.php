<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | WebsiteMonitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" href="{{ asset('assets/image/logo/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/wm.css?v=1.9') }}">
</head>
<body>
    <header id="header">
        <nav class="navbar navbar-expand-lg pt-3 pb-2">
            <div class="container-fluid">
                <a href="{{ route('wm.index') }}"><img src="{{ asset('assets/image/logo/logo1.png') }}" alt="WebsiteMonitoring logo" width="300"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse ms-3" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
{{--                        <li class="nav-item me-3">--}}
{{--                            <a class="nav-link active" aria-current="page" href="{{ route('wm.index') }}">Dashboard</a>--}}
{{--                        </li>--}}
                    </ul>
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item mt-2">
                            <p class="mb-0"><i class="fa-solid fa-user"></i> {{ $user->name }}</p>
                        </li>
                        <li class="nav-item ms-2">
                            <form action="{{ route('wm.logout') }}" method="post" id="logout-form">
                                @csrf
                                <a class="btn btn-outline-secondary" href="#" data-bs-toggle="modal" data-bs-target="#confirmLogoutModal">Logout<i class="fa-solid fa-right-from-bracket ms-1"></i></a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                {{--Noi dung cac tabs--}}
            </div>
        </nav>
    </header>
    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row" id="main-content">
            <div class="col-lg-8 pt-2 monitor-featured" id="featured-monitoring">
                <div class="row-monitors tab-content" id="main-side-content">
                    {{--Noi dung cac monitors--}}
                </div>
            </div>
            <div class="col-lg-4 monitor-detail-stage">
                <div class="row sidebar-item pb-4">
                    <div class="col-xl-4 add-monitor pe-0 pt-3">
                        <a class="btn btn-warning btn-large new-monitor-btn" href="#" data-bs-toggle="modal" data-bs-target="#newMonitorModal"><i class="fa-solid fa-plus"></i> New Monitor</a>
                    </div>
                    <div class="col-xl-8 search-global pt-3">
                        <input class="form-control search" type="search" placeholder="Search">
                    </div>
                </div>
                <div class="row log-errors-title p-2 bg-danger text-white">
                    <p class="m-0 fw-bold col-6 my-auto">Log Incidents</p>
{{--                    <button type="button" class="btn btn-outline-light col-6">Notification settings</button>--}}
                </div>
                <div class="row" id="main-side-menu">
                    <div class="table-responsive p-0">
                        <table class="table table-bordered table-hover m-0">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Status</th>
                                    <th>Domain</th>
                                    <th>Check</th>
                                </tr>
                            </thead>
                            <tbody id="setIncidentTable">
                                {{--Noi dung bang log incidents--}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal New Monitor -->
    <div class="modal fade" id="newMonitorModal" tabindex="-1" aria-labelledby="newMonitorModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content ms-5 me-5">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 p-2 d-flex justify-content-center">
                            <div class="card new-monitor w-100">
                                <div class="card-header"><i class="fa-regular fa-pen-to-square"></i> <span>New Monitor</span></div>
                                <div class="card-body">
                                    <form method="post" action="{{ route('api.wm.store') }}" id="formInput">
                                        @csrf
                                        <h5 class="card-title">Monitor Information</h5>
                                        <div class="mb-3 row">
                                            <label for="inputType" class="col-lg-3 col-form-label">Monitor Type <span class="required">*</span></label>
                                            <div class="col-lg-9">
                                                <select class="form-select" aria-label="Default select example" name="type" id="inputType">
                                                    <option value="0" selected>https</option>
                                                    <option value="1">http</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="inputURL" class="col-lg-3 col-form-label">URL <span class="required">*</span></label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" id="inputURL" name="url">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="inputDestIP" class="col-lg-3 col-form-label">Dest IP</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" id="inputDestIP" name="dest-ip">
                                            </div>
                                        </div>
                                        <div class="row form-check form-switch d-flex align-items-center" style="padding-left: 47px">
                                            <input class="form-check-input col-3" type="checkbox" role="switch" id="inputFeatured" name="featured">
                                            <label class="form-check-label col-9 me-2 pe-5" for="inputFeatured">Featured Monitor</label>
                                        </div>
                                    </form>
                                    <div id="notice">
                                        {{--Noi dung thong bao khi tao monitor moi--}}
                                    </div>
                                </div>
                            </div>
                        </div>
{{--                        <div class="col-md-5 p-2 d-flex justify-content-center">--}}
{{--                            <div class="card new-monitor w-100">--}}
{{--                                <div class="card-header"><i class="fa-solid fa-user-group"></i> <span>Select Alert Contacts</span></div>--}}
{{--                                <div class="card-body">--}}
{{--                                    <h5 class="card-title">Under maintenance</h5>--}}
{{--                                    <p class="card-text">.....</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnCreate">Create Monitor</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Set Featured -->
    <div id="set-featured-modal">
        {{--Noi dung cac modal set featured--}}
    </div>
    <!-- Modal New Tab -->
    <div class="modal fade" id="addTab" tabindex="-1" aria-labelledby="addTab" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTabLabel">New tab</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-5">
                    <form method="post" action="{{ route('api.wm.addNewTab') }}" id="formInputNewTab">
                        @csrf
                        <div class="row">
                            <label for="inputNameTab" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputNameTab" name="name-tab">
                            </div>
                        </div>
                    </form>
                    <div id="nameTabError"></div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success me-2" id="btnAddNewTab">Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Logout -->
    <div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmLogoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-5">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger me-2" onclick="confirmLogout()">Logout</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $.ajax({ //render ra cac tabs
                url: '{{ route('api.wm.renderTabs') }}',
                method: 'GET',
                success: function(tabs) {
                    renderTabs(tabs);

                    const currentUrl = window.location.search;
                    const urlParams = new URLSearchParams(currentUrl);
                    const tab = urlParams.get('tab') ? urlParams.get('tab') : 1;
                    const navTabElement = document.getElementById(`nav-${tab}-tab`);
                    const tabId = navTabElement ? tab : 1;
                    updateActiveTab(tabId);
                },
                error: function(error) {
                    console.error('Lỗi khi lấy dữ liệu từ máy chủ - renderTabs()', error);
                }
            });
            getHomePage(); //goi ham render monitors 20s/lan
        });

        function getHomePage() { //render monitors 20s/lan
            $.ajax({
                url: '{{ route('api.wm.index') }}',
                method: 'GET',
                success: async function(data) {
                    $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                    await renderMonitors(data);
                    const currentUrl = window.location.search;
                    const urlParams = new URLSearchParams(currentUrl);
                    const tabId = urlParams.get('tab') ? urlParams.get('tab') : 1;
                    renderIncidents(tabId); //render incidents cung luc voi render monitors
                    $('[data-bs-toggle="tooltip"]').tooltip();
                    setTimeout(getHomePage, 20000);
                },
                error: function(error) {
                    console.error('Lỗi khi lấy dữ liệu từ máy chủ - getHomePage()', error);
                    setTimeout(getHomePage, 20000);
                }
            });
        }

        function updateSetFeaturedTable(data) { //render ra noi dung trong bang monitors va thay doi featured khi switch trang thai cua monitor
            const monitorTabs = {};
            const allTabs = {};
            data.monitors.forEach(function (monitor) { //chia cac monitors vao dung tab cua no
                const groupId = monitor.tab_id;
                if (!monitorTabs[groupId]) {
                    monitorTabs[groupId] = [];
                }
                monitorTabs[groupId].push(monitor);
            });
            data.tabs.forEach(function (tab) { //lay ra tat ca tabs ke ca khong co monitor
                allTabs[tab] = true;
            });
            for (let groupId in allTabs) {
                let setFeaturedTableHtml = `<tr>
                                                <td colspan="3" class="text-center">No Data Found</td>
                                            </tr>`;
                if (monitorTabs[groupId] && monitorTabs[groupId].length > 0) {
                    setFeaturedTableHtml = '';
                    monitorTabs[groupId].forEach(function (monitor) {
                        setFeaturedTableHtml += `<tr>
                                                    <td>
                                                        <div class="form-check form-switch d-flex justify-content-center">
                                                            <input class="form-check-input form-check-input-monitor mx-auto"
                                                                type="checkbox" role="switch" id="flexSwitchCheckChecked-${monitor.id}"
                                                                ${monitor.featured ? 'checked' : ''} name="display-status" data-monitor-id="${monitor.id}">
                                                        </div>
                                                    </td>
                                                    <td>${monitor.url}</td>
                                                    <td class="text-center">${monitor.type}</td>
                                                </tr>`;
                    });
                }

                $(`#set-featured-table-${groupId}`).html(setFeaturedTableHtml); //set noi dung cac bang khac nhau cua tab tuong ung
            }
            //UPDATE STATUS PRODUCT
            const checkboxProducts = document.querySelectorAll(".form-check-input-monitor");
            checkboxProducts.forEach(checkbox => { //cai dat su kien khi bam vao checkbox switch trang thai featured
                checkbox.addEventListener("click", function () {
                    const isChecked = checkbox.checked;
                    const monitorId = checkbox.getAttribute('data-monitor-id');
                    const currentUrl = window.location.search;
                    const urlParams = new URLSearchParams(currentUrl);
                    const tabId = urlParams.get('tab') ? urlParams.get('tab') : 1;
                    updateFeaturedMonitor(isChecked, monitorId, tabId);
                });
            });
        }

        function updateFeaturedMonitor(isChecked, monitorId, tabId) { //ham api de switch trang thai featured - sau do render lai monitors
            fetch(`/website-monitoring/dashboard/${monitorId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ isChecked, tabId }),
            })
            .then(response => {
                if (response.ok) {
                    $.ajax({
                        url: '{{ route('api.wm.index') }}',
                        method: 'GET',
                        success: async function(data) {
                            await renderMonitors(data);
                            renderIncidents(tabId);
                        },
                        error: function(error) {
                            console.error('Lỗi khi lấy dữ liệu từ máy chủ - updateFeaturedMonitor()', error);
                        }
                    });
                } else {
                    throw new Error(`Request failed with status: ${response.status}`);
                }
            });
        }

        const addLeadingZero = (num) => (num < 10 ? "0" + num : num); //them so 0 truoc ngay/thang/nam/gio/phut/giay

        async function renderMonitors(data) {
            const monitorTabs = {};
            data.monitors.forEach(function(monitor) { //chia cac monitors vao dung tab cua no
                const groupId = monitor.tab_id;
                if (!monitorTabs[groupId]) {
                    monitorTabs[groupId] = [];
                }
                monitorTabs[groupId].push(monitor);
            });
            data.tabs.forEach(function(tab) {
                let tabContentHtml = ''; //them html noi dung cua cac tab
                if(monitorTabs[tab.id]) {
                    tabContentHtml += `<div class="row">`;
                    monitorTabs[tab.id].forEach(function(monitor) {
                        const statusMessages = data.statusMessages[monitor.url];
                        const uptimeRecords = monitor.uptime_records;
                        const latestUptimeRecord = uptimeRecords[0] ? uptimeRecords[0] : 0;
                        let status = '';
                        if (latestUptimeRecord) {
                            if(parseInt(latestUptimeRecord.status_code) >= 500) {//Response time >= 10s
                                status = 'down-website';
                            } else if (parseInt(latestUptimeRecord.status_code) === 200) {
                                status = 'up-website';
                            } else {
                                status = 'slow-website';
                            }
                        }
                        const date = new Date(latestUptimeRecord ? latestUptimeRecord.checked_at * 1000 : 0);
                        const hours = addLeadingZero(date.getHours());
                        const minutes = addLeadingZero(date.getMinutes());
                        const seconds = addLeadingZero(date.getSeconds());
                        const day = addLeadingZero(date.getDate());
                        const month = addLeadingZero(date.getMonth() + 1);
                        const year = date.getFullYear();

                        const dateFormat = hours + ":" + minutes + ":" + seconds + ", " + day + "/" + month + "/" + year;

                        tabContentHtml += `<div class="col-lg-4 col-sm-6">`;
                        tabContentHtml += `<div class="card ${status} mb-3 w-100">`;
                        tabContentHtml += `<div class="card-header" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                             <a href="${monitor.type + "://" + monitor.url}" target="_blank" class="text-black"
                                             onmouseover="this.style.textDecoration='underline';"
                                             onmouseout="this.style.textDecoration='none';">${monitor.url}</a></div>`;
                        tabContentHtml += `<div class="card-body p-2">`;
                        tabContentHtml += `<i class="fa-solid fa-gauge-high"></i> ${latestUptimeRecord ? latestUptimeRecord.response_time/1000 : 0}s`;
                        tabContentHtml += `<i class="fa-solid fa-calendar-days ms-4"></i> ${dateFormat}`;
                        tabContentHtml += `<p class="m-0 mt-2 mb-2">`;

                        uptimeRecords.reverse().forEach(function(uptimeRecord) {
                            const date = new Date(uptimeRecord.checked_at ? uptimeRecord.checked_at * 1000 : 0);
                            const hours = addLeadingZero(date.getHours());
                            const minutes = addLeadingZero(date.getMinutes());
                            const seconds = addLeadingZero(date.getSeconds());
                            const day = addLeadingZero(date.getDate());
                            const month = addLeadingZero(date.getMonth() + 1);
                            const year = date.getFullYear();

                            const dateFormat = hours + ":" + minutes + ":" + seconds + ", " + day + "/" + month + "/" + year;
                            let statusUptimeRecord = '';
                            if (parseInt(uptimeRecord.status_code) >= 500) {
                                statusUptimeRecord = 'text-danger';
                            } else if(parseInt(uptimeRecord.status_code) === 200) {
                                statusUptimeRecord = 'text-success';
                            } else {
                                statusUptimeRecord = 'text-warning';
                            }

                            const statusMessage = statusMessages.pop();
                            tabContentHtml += `<span class="m-0 fs-6 tt-utr" data-bs-toggle="tooltip" data-bs-placement="top" title="Status: ${uptimeRecord.status_code} - ${statusMessage}\nChecked at: ${dateFormat}\nResponse time: ${uptimeRecord.response_time/1000}s" data-html="true"><i class="fa-solid fa-circle fa-2xs ${statusUptimeRecord} me-1"></i></span>`;

                            $(function() {
                                $('.tt-utr').tooltip();
                            });
                        });
                        tabContentHtml += '</p>';
                        // tabContentHtml += '<p>';
                        // tabContentHtml += `<p class="m-0">Status now: ${latestUptimeRecord.status_code}</p>`;
                        tabContentHtml += '</div>';
                        tabContentHtml += '</div>';
                        tabContentHtml += '</div>';
                    });
                    tabContentHtml += '</div>';
                }
                $(`#nav-${tab.id} #navContent`).html(tabContentHtml);
            });

            const currentUrl = window.location.search;
            const urlParams = new URLSearchParams(currentUrl);
            const tabId = urlParams.get('tab') ? urlParams.get('tab') : 1;
            renderIncidents(tabId); //render incidents cung luc voi render monitors
        }

        function renderTabs(tabs) {
            let navTabHtml = ''; //them html cac tab, nut them tab va nut xoa tab
            let tabContentHtml = ''; //them html khung noi dung cua cac tab
            let setFeaturedModalHtml = ''; //them khung bang set featured cua monitors
            let inputTabToCreate = ''; //them cac ten cua tab vao phan New Monitor
            if (tabs.length > 0) {
                tabs.forEach(function(tab, index) {
                    navTabHtml += `<button class="nav-link nav-tab pe-1 d-flex justify-content-between align-items-center ${index === 0 ? 'active' : ''}" id="nav-${tab.id}-tab" data-bs-toggle="tab" data-bs-target="#nav-${tab.id}" type="button" role="tab" aria-controls="nav-${tab.id}" aria-selected="true" onclick="handleTabClick(event, ${tab.id})">
                                        <span class="fs-6">${tab.name}</span>
                                        <i class="fa-solid fa-xmark icon-exit-tab ${index === 0 ? 'hidden' : ''}" id="${tab.id}" data-bs-toggle="modal" data-bs-target="#confirmRemoveTabModal-${tab.id}"></i>
                                   </button>
                                    <div class="modal fade" id="confirmRemoveTabModal-${tab.id}" tabindex="-1" aria-labelledby="confirmRemoveTabModal-${tab.id}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered text-center">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmLogoutModalLabel">Confirm Remove Tab</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body fs-5">
                                                    Are you sure you want to close this tab?<br> <span class="text-danger fs-4">This action will result in the deletion of all monitors associated with this tab.</span>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-center">
                                                    <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-danger me-2" onclick="confirmRemoveTab(${tab.id})">Close Tab</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                    tabContentHtml += `<div class="tab-pane fade  ${index === 0 ? 'show active' : ''}" id="nav-${tab.id}" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                                            <div class="tools d-flex justify-content-between align-items-center">
                                                <p class="mb-2 btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#setFeaturedModal-${tab.id}"><span class="badge">Set Featured</span></p>
                                                <span class="loader loading d-none"></span>
                                            </div>
                                            <div id="navContent">
                                                <!--Noi dung cua cac tab-->
                                            </div>
                                        </div>`;
                    setFeaturedModalHtml += `<div class="modal fade" id="setFeaturedModal-${tab.id}" tabindex="-1" aria-labelledby="setFeaturedModal-${tab.id}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Set Featured Monitor</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card-body table-responsive">
                                                                <table class="table table-bordered table-hover">
                                                                    <thead class="table-dark">
                                                                        <tr>
                                                                            <th class="text-center">Featured</th>
<!--                                                                            <th>Friendly Name</th>-->
                                                                            <th>URL</th>
                                                                            <th class="text-center">Type</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="set-featured-table-${tab.id}">
                                                                        <!--Noi dung cua bang set featured-->
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                    inputTabToCreate += `<option value="${tab.id}">${tab.name}</option>`;
                });
            }
            navTabHtml += `<button class="add-tab btn btn-sm" role="tab" data-bs-toggle="modal" data-bs-target="#addTab">
                                <i class="fa-solid fa-plus"></i>
                           </button>`;

            $('#nav-tab').html(navTabHtml);
            $('#main-side-content').html(tabContentHtml);
            $('#set-featured-modal').html(setFeaturedModalHtml);
            $('#inputTab').html(inputTabToCreate);

            $.ajax({ //cap nhat lai bang set featured khi render tabs
                url: '{{ route('api.wm.getTableMonitor') }}',
                method: 'GET',
                success: function(data) {
                    updateSetFeaturedTable(data);
                },
                error: function(error) {
                    console.error('Lỗi khi lấy dữ liệu từ máy chủ - renderTabs()', error);
                }
            });
        }

        function handleTabClick(event, tabId) {
            event.preventDefault();
            renderIncidents(tabId);

            history.pushState(null, null, `/website-monitoring/dashboard?tab=${tabId}`);
        }

        function renderIncidents(tabId) {
            $.ajax({
                url: '{{ route('api.wm.renderIncidents') }}',
                data: { tabId: tabId },
                method: 'GET',
                success: function(incidents) {
                    let setIncidentTableHtml = ''; // them html cho cac incidents
                    if (incidents.length > 0) {
                        incidents.forEach(function(incident) {
                            let statusIncident = '';
                            if (parseInt(incident.latest_status_code) >= 500) {
                                statusIncident = `<i class="fa-regular fa-circle text-danger"></i> <br>${incident.latest_status_code}`;
                            } else {
                                statusIncident = `<i class="fa-regular fa-circle text-warning"></i> <br>${incident.latest_status_code}`;
                            }
                            if (incident) {
                                const date = new Date(incident.latest_checked_at * 1000);
                                const hours = addLeadingZero(date.getHours());
                                const minutes = addLeadingZero(date.getMinutes());
                                const seconds = addLeadingZero(date.getSeconds());
                                const day = addLeadingZero(date.getDate());
                                const month = addLeadingZero(date.getMonth() + 1);
                                const year = date.getFullYear();
                                const dateFormat = hours + ":" + minutes + ":" + seconds + ", " + day + "/" + month + "/" + year;

                                setIncidentTableHtml += `<tr class="tt-incident" data-bs-toggle="tooltip" data-bs-placement="top" title="Started at: ${incident.started_at}">
                                                            <td class="text-center">${statusIncident}</td>
                                                            <td>
                                                                <a href="${incident.type}://${incident.url}" target="_blank" class="text-black"
                                                                   onmouseover="this.style.textDecoration='underline';"
                                                                   onmouseout="this.style.textDecoration='none';">${incident.name}</a>
                                                                <p class="m-0 text-black">Latest check: ${dateFormat}</p>
                                                            </td>
                                                            <td class="text-center">${incident.count}</td>
                                                        </tr>`;

                                $(function() {
                                    $('.tt-incident').tooltip();
                                });
                            }
                        });
                    } else {
                        setIncidentTableHtml += `<tr>
                                                    <td colspan="3" class="text-center">No Incident Found</td>
                                                 </tr>`;
                    }
                    $('#setIncidentTable').html(setIncidentTableHtml);
                },
                error: function(error) {
                    console.error('Lỗi khi lấy dữ liệu từ máy chủ - renderIncidents()', error);
                }
            });
        }

        function submitForm (form) { //gui form tao moi monitor
            const inputType = document.getElementById('inputType');
            const inputURL = document.getElementById('inputURL' );
            const inputFeatured = document.getElementById('inputFeatured');
            const formData = new FormData(form);

            const currentUrl = window.location.search;
            const urlParams = new URLSearchParams(currentUrl);
            const tabId = urlParams.get('tab') ? urlParams.get('tab') : 1;
            formData.append('tab_id', tabId);
            $.ajax({
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                url: '{{ route('api.wm.store') }}',
                success: function(results) {
                    let noticeContent = '';
                    if (typeof results === 'object') { //tao moi khong thanh cong => ket qua la mot object error
                        noticeContent += `<div class="alert alert-danger m-0 d-flex align-items-center mt-2">`;
                        noticeContent += `<ul class="my-auto">`
                        for (let key in results) {
                            noticeContent += `<li>${results[key]}</li>`;
                            break;
                        }
                        noticeContent += `</ul>`;
                        noticeContent += `</div>`;

                        const notice = $('#notice');
                        notice.html(noticeContent).fadeIn(750);

                        notice.delay(1500).fadeOut(750, function() {
                            $(this).empty();
                        });
                    }
                    else { //tao moi thanh cong
                        inputType.value = 0;
                        const event = new Event('change');
                        inputType.dispatchEvent(event);
                        noticeContent += `<div class="alert alert-success m-0 d-flex align-items-center mt-2">`;
                        noticeContent += `<p class="m-0">${results}</p>`;
                        noticeContent += `</div>`;

                        inputURL.value = "";
                        inputFeatured.checked = false;

                        const notice = $('#notice');
                        notice.html(noticeContent).fadeIn(750);

                        notice.delay(1500).fadeOut(750, function() {
                            $(this).empty();
                        });

                        $.ajax({
                            url: '{{ route('api.wm.getTableMonitor') }}',
                            method: 'GET',
                            success: function(data) {
                                getHomePage();
                                updateSetFeaturedTable(data);
                            },
                            error: function(error) {
                                console.error('Lỗi khi lấy dữ liệu từ máy chủ - renderTabs()', error);
                            }
                        });
                    }
                }
            });
        }

        const btnCreate = document.getElementById('btnCreate');
        btnCreate.addEventListener('click', function (e) {
            e.preventDefault();
            const form = document.getElementById('formInput');
            submitForm(form); //bam nut create monitor thi gui form de them vao database
        });

        //LOGOUT
        function confirmLogout() {
            document.getElementById('logout-form').submit();
        }

        //ADD A NEW TAB
        function getTabs() { //render tabs va cap nhat lai tab active
            $.ajax({
                url: '{{ route('api.wm.renderTabs') }}',
                method: 'GET',
                success: function(tabs) {
                    renderTabs(tabs);

                    const newTabId = tabs[tabs.length - 1].id;
                    updateActiveTab(newTabId);
                },
                error: function(error) {
                    console.error('Lỗi khi lấy dữ liệu từ máy chủ', error);
                }
            });
            getHomePage();
        }

        function submitFormAddNewTab (form) { //gui form tao moi tab
            const formData = new FormData(form);
            $.ajax({
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                url: '{{ route('api.wm.addNewTab') }}',
                success: function (results) {
                    console.log(!results);
                    if(!results) {
                        let noticeContent = '';
                        noticeContent += `<div class="alert alert-danger m-0 d-flex align-items-center mt-2">
                                <p class="m-0 fs-6">Name tab is required.</p>
                              </div>`;

                        const nameTabError = $('#nameTabError');

                        nameTabError.html(noticeContent).fadeIn(750);
                        nameTabError.delay(1500).fadeOut(750, function() {
                            $(this).empty();
                        });
                    } else {
                        $('#addTab').modal('hide');
                        $('#inputNameTab').val('');
                        getTabs();
                    }
                },
            });
        }

        document.getElementById('inputNameTab').addEventListener('keydown', function(event) { //huy nut enter khi tao tab
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
        $('#addTab').on('shown.bs.modal', function () {
            $('#inputNameTab').focus();
        });

        const btnAddNewTab = document.getElementById('btnAddNewTab');
        btnAddNewTab.addEventListener('click', function (e) {
            e.preventDefault();
            const form = document.getElementById('formInputNewTab');
            submitFormAddNewTab(form);
        });

        function updateActiveTab (tabId) {
            const tabs = document.querySelectorAll('.nav-link.nav-tab');
            const currentActiveTab = document.querySelector('.nav-link.nav-tab.active');
            const currentActiveTabPane = document.querySelector('.tab-pane.active.show');

            if (currentActiveTab) {
                currentActiveTab.classList.remove('active');
            }
            if (currentActiveTabPane) {
                currentActiveTabPane.classList.remove('show', 'active');
            }
            if (tabs.length > 0) {
                const showTab = document.getElementById(`nav-${tabId}-tab`);
                const showTabPane = document.getElementById(`nav-${tabId}`);
                if (showTab) {
                    showTab.classList.add('active');
                }
                if (showTabPane) {
                    showTabPane.classList.add('show', 'active');
                }
            }
            history.pushState(null, null, `/website-monitoring/dashboard?tab=${tabId}`);
        }

        //REMOVE TAB
        function confirmRemoveTab(tabId) {
            $.ajax({
                url: `/api/website-monitoring/dashboard/remove-tab/${tabId}`,
                type: 'DELETE',
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function (response) {
                    getTabs();
                },
                error: function (error) {
                    console.error('Error deleting tabs:', error);
                }
            });
            $(`#confirmRemoveTabModal-${tabId}`).modal('hide');
        }
    </script>
</body>
</html>
