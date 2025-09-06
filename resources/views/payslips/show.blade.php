@extends('layouts.app')
@section('content')
    <div class="app__slide-wrapper">
        <div class="breadcrumb__area">
            <div class="breadcrumb__wrapper mb-25">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payslip</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12 col-md-12">
                <div class="card__wrapper">
                    <div class="mb-20 text-center">
                        <h5 class="card__heading-title">Employee Payslip</h5>
                    </div>
                    <div class="d-flex justify-content-between flex-xl-row flex-sm-row flex-column">
                        <div class="payslip__office-address">
                            <p>100 Terminal, Fort Lauderdale,</p>
                            <p>Miami 33315, United States</p>
                            <p>+1(800) 642 7676</p>
                        </div>
                        <div class="payslip__serial-number">
                            <div class="mb-10">
                                <h5 class="card__heading-title">PAYSLIP #{{ $data->id }}</h5>
                            </div>
                            <div class="mb-5">
                                <span>Issued Date:</span>
                                <span>{{ $data->date_issued }}</span>
                            </div>
                            <div class="mb-5">
                                <span>Date Due:</span>
                                <span>{{ $data->due_date }}</span>
                            </div>
                            <div class="mb-5">
                                <span>Status:</span>
                                <span>{{ strtoupper($data->status) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="payslip-line"></div>
                    <div class="row g-60 gy-20">
                        <div class="col-xl-6 col-lg-6 col-sm-6">
                            <div class="mb-20">
                                <h4>Billing Address</h4>
                            </div>
                            <div class="payslip__employee-address">
                                <h5 class="mb-10 fw-600">{{ $data->user->name }}</h5>
                                <p class="text-muted">Position: <span>{{ $data->user->getMeta('designation') }}</span> </p>
                                <p class="text-muted">Department: <span>{{ $data->user->getDepartment() }}</span></p>
                                <p class="text-muted">Email: <span>{{ $data->user->email }}</span>
                                </p>
                                <p class="text-muted">Phone:<span> {{ $data->user->getMeta('phone') }}</span></p>
                            </div>
                        </div>
                    </div>
                    @php
                        $earnings = $data->payslipsData->where('type', 'earning');
                        $deductions = $data->payslipsData->where('type', 'deduction');
                    @endphp
                    <div class="payslip-line"></div>
                    <div class="payslip__table table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead>
                                <tr class="table__title bg-title">
                                    <th scope="col">Earning</th>
                                    <th scope="col" style="width: 350px;">Title</th>
                                    <th scope="col" style="width: 200px;">Type</th>
                                    <th scope="col" class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($earnings as $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->description}}</td>
                                        <td>{{$item->pay_type}}</td>
                                        <td class="payslip-amount text-end">${{$item->amount}}</td>
                                    </tr>
                                @endforeach
                                <tr class="payslip-total">
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td class="text-end">${{$earnings->sum('amount')}}</td>
                                        </tr>
                                
                               
                            </tbody>
                            <thead>
                                <tr class="table__title bg-title">
                                    <th scope="col">Deduction</th>
                                    <th scope="col" style="width: 350px;">Title</th>
                                    <th scope="col" style="width: 200px;">Type</th>
                                    <th scope="col" class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deductions as $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->description}}</td>
                                        <td>{{$item->pay_type}}</td>
                                        <td class="payslip-amount text-end">${{$item->amount}}</td>
                                    </tr>
                                @endforeach
                                <tr class="payslip-total">
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td class="text-end">${{$deductions->sum('amount')}}</td>
                                        </tr>
                                <tr class="payslip-grand-total">
                                    <td></td>
                                    <td></td>
                                    <td>Grand Total</td>
                                    <td class="text-end">${{$earnings->sum('amount') - $deductions->sum('amount')}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="payslip__payment-details mt-25">
                        <h5 class="card__heading-title mb-15">Payment Details:</h5>
                        <p class="text-muted">Payment Method: <span>Bank Account</span></p>
                        <p class="text-muted">Account Name: <span>{{ $data->user->getMeta('account_holder_name') }}</span></p>
                        <p class="text-muted">Account Number: <span>{{$data->user->getMeta('account_number')}}</span></p>
                        <p class="text-muted">Account Name: <span>{{$data->user->getMeta('bank_name')}}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- App side area end -->
@endsection
