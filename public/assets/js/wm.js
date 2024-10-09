$(document).ready(function(){
    $.ajax({ //render ra cac tabs
        url: '/api/website-monitoring/dashboard/tabs',
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
        url: '/api/website-monitoring/dashboard',
        method: 'GET',
        success: async function(data) {
            await renderMonitors(data);
            const currentUrl = window.location.search;
            const urlParams = new URLSearchParams(currentUrl);
            const tabId = urlParams.get('tab') ? urlParams.get('tab') : 1;
            renderIncidents(tabId); //render incidents cung luc voi render monitors
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
    })
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
            updateFeaturedMonitor(isChecked, monitorId);
        });
    });
}

function updateFeaturedMonitor(isChecked, monitorId) { //ham api de switch trang thai featured - sau do render lai monitors
    fetch(`/website-monitoring/dashboard/${monitorId}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ isChecked }),
    })
        .then(response => {
            if (response.ok) {
                $.ajax({
                    url: '/api/website-monitoring/dashboard',
                    method: 'GET',
                    success: async function(data) {
                        await renderMonitors(data);
                        const currentUrl = window.location.search;
                        const urlParams = new URLSearchParams(currentUrl);
                        const tabId = urlParams.get('tab') ? urlParams.get('tab') : 1;
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
                const uptimeRecords = monitor.uptime_records;
                const latestUptimeRecord = uptimeRecords[0] ? uptimeRecords[0] : 0;
                let status = '';
                if (latestUptimeRecord) {
                    if(latestUptimeRecord.response_time >= 10*1000) {//Response time >= 10s
                        status = 'slow-website';
                    } else if (latestUptimeRecord.is_up) {
                        status = 'up-website';
                    } else {
                        status = 'down-website';
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
                if (monitor.featured) {
                    tabContentHtml += `<div class="col-lg-4 col-sm-6">`;
                    tabContentHtml += `<div class="card ${status} mb-3 w-100" style="margin-bottom: 0;">`;
                    tabContentHtml += `<div class="card-header" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;" title="${monitor.url}">
                                                 <a href="${monitor.type + "://" + monitor.url}" target="_blank" class="text-black"
                                                 onmouseover="this.style.textDecoration='underline';"
                                                 onmouseout="this.style.textDecoration='none';">${monitor.url}</a></div>`;
                    tabContentHtml += `<div class="card-body p-2">`;
                    tabContentHtml += `<i class="fa-solid fa-gauge-high"></i> ${latestUptimeRecord ? latestUptimeRecord.response_time/1000 : 0}s`;
                    tabContentHtml += `<i class="fa-solid fa-calendar-days ms-4"></i> ${dateFormat}`
                    tabContentHtml += `<p class="m-0">`;

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
                        if (uptimeRecord.response_time >= 10*1000) {
                            statusUptimeRecord = 'text-warning';
                        } else if(uptimeRecord.is_up) {
                            statusUptimeRecord = 'text-success';
                        } else {
                            statusUptimeRecord = 'text-danger';
                        }

                        tabContentHtml += `<i class="fa-solid fa-circle fa-2xs ${statusUptimeRecord} me-1" title="Status: ${uptimeRecord.status_code} | Checked at: ${dateFormat} | Response time: ${uptimeRecord.response_time/1000}s"></i>`;
                    });
                    tabContentHtml += '</p>';
                    tabContentHtml += '<p>';
                    tabContentHtml += '</div>';
                    tabContentHtml += '</div>';
                    tabContentHtml += '</div>';
                }
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
        url: '/api/website-monitoring/dashboard/get-table-monitor',
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
        url: '/api/website-monitoring/dashboard/incidents',
        data: { tabId: tabId },
        method: 'GET',
        success: function(incidents) {
            let setIncidentTableHtml = ''; // them html cho cac incidents
            if (incidents.length > 0) {
                incidents.forEach(function(incident) {
                    let statusIncident = '';
                    if (incident.response_time >= 10*1000) { //response time >= 10s
                        statusIncident = `<i class="fa-regular fa-circle text-warning"></i>`;
                    } else {
                        statusIncident = `<i class="fa-regular fa-circle text-danger"></i>`;
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

                        setIncidentTableHtml += `<tr title="Started at: ${incident.started_at} | Status: ${incident.latest_status_code}">
                                                    <td class="text-center">${statusIncident}</td>
                                                    <td>
                                                        <a href="${incident.type}://${incident.url}" target="_blank" class="text-black"
                                                           onmouseover="this.style.textDecoration='underline';"
                                                           onmouseout="this.style.textDecoration='none';">${incident.name}</a>
                                                        <p class="m-0 text-black">Latest check: ${dateFormat}</p>
                                                    </td>
                                                    <td class="text-center">${incident.count}</td>
                                                </tr>`;
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
        url: '/api/website-monitoring/dashboard',
        success: function(results) {
            let noticeContent = '';
            if (typeof results === 'object') { //tao moi khong thanh cong => ket qua la mot object error
                noticeContent += `<div class="alert alert-danger m-0 d-flex align-items-center mt-2">`;
                noticeContent += `<ul class="my-auto">`;
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
                    url: '/api/website-monitoring/dashboard/get-table-monitor',
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
        url: '/api/website-monitoring/dashboard/tabs',
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
        url: '/api/website-monitoring/dashboard/store-new-tab',
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
