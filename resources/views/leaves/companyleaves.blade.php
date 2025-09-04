@extends('layouts.app')

@section('content')
    <div class="app__slide-wrapper">
        <div class="breadcrumb__area">
            <div class="breadcrumb__wrapper mb-25">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Leave Admin</li>
                    </ol>
                </nav>
             
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-light fa-user"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Total Employee</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">{{ $emp_count }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-light fa-badge-check"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Approve</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">{{ collect($leaves)->where('status', 'approved')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-sharp fa-regular fa-ban"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Rejected </h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">{{ collect($leaves)->where('status', 'rejected')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-sharp fa-regular fa-house-person-leave"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Pending</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">{{ collect($leaves)->where('status', 'pending')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-12">
                <div class="card__wrapper">
                    <div class="table__wrapper table-responsive">
                        <table class="table mb-20" id="dataTableDefualt">
                            <thead>
                                <tr class="table__title">
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Leave Type</th>
                                    <th>Leave Date</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    @can('action leaves')
                                        <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="table__body">
                                @foreach ($leaves as $item)
                                    <tr>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->getMeta('designation') }}</td>
                                        <td>{{ ucwords($item->type) }}</td>
                                        <td>{{ date('d F, Y', $item->date) }}</td>
                                        <td>{{ $item->reason ?? 'Reason Not defined' }}</td>
                                        <td>
                                            <span
                                                class="bd-badge bg-{{ $item->badge }}">{{ ucwords($item->status) }}</span>
                                        </td>
                                        @can('action leaves')
                                            <td>
                                                <form id="statusForm-{{ $item->id }}"
                                                    action="{{ route('leave.updateStatus', $item->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <input type="hidden" name="status" id="statusInput-{{ $item->id }}">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if ($item->status != "pending")
                                                                <li><a class="dropdown-item" href="#"
                                                                    onclick="submitStatus('{{ $item->id }}', 'pending')">Pending</a></li>
                                                            @endif
                                                            @if ($item->status != "approved")
                                                            
                                                                <li><a class="dropdown-item" href="#"
                                                                    onclick="submitStatus('{{ $item->id }}', 'approved')">Approved</a></li>
                                                                    @endif
                                                            
                                                            @if ($item->status != "rejected")
                                                                
                                                                <li><a class="dropdown-item" href="#"
                                                                    onclick="submitStatus('{{ $item->id }}', 'rejected')">Rejected</a></li>
                                                            @endif

                                                         
                                                        </ul>
                                                      </div>
                                                    {{-- <div class="dropdown-basic">
                                                        <div class="dropdown">
                                                            <i class="fa-solid fa-caret-down"></i>
                                                            <div class="dropdown-content">
                                                                
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                </form>

                                            </td>
                                        @endcan

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function submitStatus(leaveId, status) {
            document.getElementById("statusInput-" + leaveId).value = status;
            document.getElementById("statusForm-" + leaveId).submit();
        }
    </script>
@endpush
