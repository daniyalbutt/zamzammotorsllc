@props(['data'])
<div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-6">
    <div class="card__wrapper">
        <div class="d-flex align-items-center gap-sm">
            <div class="card__icon">
                <span><i class="fa-sharp fa-regular fa-user"></i></span>
            </div>
            <div class="card__title-wrap">
                <h6 class="card__sub-title mb-10">Total Employee</h6>
                <div class="d-flex flex-wrap align-items-end gap-10">
                    <h3 class="card__title">{{ $data['totalEmployee'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-6">
    <div class="card__wrapper">
        <div class="d-flex align-items-center gap-sm">
            <div class="card__icon">
                <span><i class="fa-sharp fa-regular fa-check"></i></span>
            </div>
            <div class="card__title-wrap">
                <h6 class="card__sub-title mb-10">Total Present Today</h6>
                <div class="d-flex flex-wrap align-items-end gap-10">
                    <h3 class="card__title">{{ $data['totalPresent'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-6">
    <div class="card__wrapper">
        <div class="d-flex align-items-center gap-sm">
            <div class="card__icon">
                <span><i class="fa-sharp fa-regular fa-house-person-leave"></i></span>
            </div>
            <div class="card__title-wrap">
                <h6 class="card__sub-title mb-10">On Leave Employee</h6>
                <div class="d-flex flex-wrap align-items-end gap-10">
                    <h3 class="card__title">{{ $data['totalLeave'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6">
    <x:annoucemnent />


</div>

<div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6">
    <div class="card__wrapper">
        <div class="card__title-wrap d-flex align-items-center justify-content-between mb-20">
            <h5 class="card__heading-title">Recent Activity</h5>
            <div class="card__dropdown">
                <div class="dropdown">
                    <button>
                        <i class="fa-regular fa-ellipsis-vertical"></i>
                    </button>
                    <div class="dropdown-list">
                        <a class="dropdown__item" href="javascript:void(0)">Action</a>
                        <a class="dropdown__item" href="javascript:void(0)">More Action</a>
                        <a class="dropdown__item" href="javascript:void(0)">Another Action</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-height-2 card__scroll">
            <div class="card__inner">
                <ul class="timeline">
                    <li class="timeline__item d-flex gap-10">
                        <div class="timeline__icon"><span><i class="fa-light fa-box"></i></span></div>
                        <div class="timeline__content w-100">
                            <div class="d-flex flex-wrap gap-10 align-items-center justify-content-between">
                                <h5 class="small">Purchased from MediaTek</h5>
                                <span class="bd-badge bg-success">04 Mins Ago</span>
                            </div>
                            <p>Successfully integrated new HRM features into the system</p>
                           
                        </div>
                    </li>
                    <li class="timeline__item d-flex gap-10">
                        <div class="timeline__icon"><span><i class="fa-light fa-box"></i></span></div>
                        <div class="timeline__content w-100">
                            <div class="d-flex flex-wrap gap-10 align-items-center justify-content-between">
                                <h5 class="small">CRM Notification</h5>
                                <span class="bd-badge bg-success">10 Mins Ago</span>
                            </div>
                            <p><span class="text-danger text-decoration-underline">3 days left</span> to update
                                customer profiles with the new CRM tools</p>
                        </div>
                    </li>
               
               
                </ul>
            </div>
        </div>
    </div>

</div>
