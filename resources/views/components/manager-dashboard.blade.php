@props(['data'])
<div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-6">
    <div class="card__wrapper">
        <div class="d-flex align-items-center gap-sm">
            <div class="card__icon">
                <span><i class="fa-sharp fa-regular fa-car"></i></span>
            </div>
            <div class="card__title-wrap">
                <h6 class="card__sub-title mb-10">Total Agent</h6>
                <div class="d-flex flex-wrap align-items-end gap-10">
                    <h3 class="card__title">{{$data['agentcount']}}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-6">
    <div class="card__wrapper">
        <div class="d-flex align-items-center gap-sm">
            <div class="card__icon">
                <span><i class="fa-sharp fa-solid fa-user"></i></span>
            </div>
            <div class="card__title-wrap">
                <h6 class="card__sub-title mb-10">Customers</h6>
                <div class="d-flex flex-wrap align-items-end gap-10">
                    <h3 class="card__title">{{$data['customercount']}}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-6">
    <div class="card__wrapper">
        <div class="d-flex align-items-center gap-sm">
            <div class="card__icon">
                <span><i class="fa-sharp fa-solid fa-car"></i></span>
            </div>
            <div class="card__title-wrap">
                <h6 class="card__sub-title mb-10">Total Assigned Cars</h6>
                <div class="d-flex flex-wrap align-items-end gap-10">
                    <h3 class="card__title">{{$data['assignedcount']}}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
