<div class="col-xxl-9 col-xl-9 col-lg-9 mx-auto">
    <div class="card__wrapper">
        <div class="card__title-wrap d-flex align-items-center justify-content-between mb-20">
            <h5 class="card__heading-title">Generate Attendance Reports</h5>
        </div>
        <div class="card__body border-top pt-15">

            <h6 class="card__heading-title attendance-head"><span class="icon-clock"></span> Company's Attendance</h6>

            <form action="{{ route('generate.csv') }}" method="POST" class="mt-20">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="month">Month</label>
                            <select name="month" id="month" class="form-control">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option {{ now()->format('Y') }} value="{{ $i }}">
                                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="year">Year</label>
                            <select name="year" id="" class="form-control">
                                @for ($i = now()->subYear(2)->format('Y'); $i <= now()->format('Y'); $i++)
                                    <option {{ $i == now()->format('Y') ? 'selected' : '' }}
                                        value="{{ $i }}">
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-center">
                        <button class="btn btn-primary">Generate</button>
                    </div>
                </div>


            </form>

        </div>



    </div>
</div>
