@extends('layouts.app')

@section('content')
    <div class="app__slide-wrapper">
        <div class="breadcrumb__area">
            <div class="breadcrumb__wrapper mb-25">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Announcement</li>
                    </ol>
                </nav>
                <div class="breadcrumb__btn">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addNewAnnouncement">Add Announcement</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12">
                <div class="card__wrapper">
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <div class="table__wrapper table-responsive">
                        <table class="table mb-20" id="dataTableDefualt">
                            <thead>
                                <tr class="table__title">
                                    <th>Title</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->start_date }}</td>
                                        <td>{{ $item->end_date }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start gap-10">
                                                <button type="button" class="table__icon edit edit-announcement"
                                                    data-id="{{ $item->id }}"
                                                    data-title="{{ $item->title }}"
                                                    data-start="{{ $item->start_date }}"
                                                    data-end="{{ $item->end_date }}"
                                                    data-description="{{ $item->description }}">
                                                    <i class="fa-sharp fa-light fa-pen"></i>
                                                    
                                                </button>
                                                <button class="removeBtn table__icon delete">
                                                    <i class="fa-regular fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
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
    <div id="addNewAnnouncement" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Announcement</h5>
                    <button type="button" class="bd-btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-xmark-large"></i></button>
                </div>
                <div class="modal-body">
                    <form id="announcementForm" method="POST" action="{{ route('announcements.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <input type="hidden" name="id" id="announcement_id">

                        <div class="card__wrapper">
                            <div class="row gy-20">
                                <div class="col-md-12">
                                    <div class="from__input-box">
                                        <div class="form__input-title">
                                            <label for="title">Title <span>*</span></label>
                                        </div>
                                        <div class="form__input">
                                            <input class="form-control" name="title" id="title" type="text">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="from__input-box">
                                        <div class="form__input-title">
                                            <label for="startingDate">Start Date <span>*</span></label>
                                        </div>
                                        <div class="form__input">
                                            <input class="form-control" name="start_date" id="startingDate" type="date"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="from__input-box">
                                        <div class="form__input-title">
                                            <label for="startingDate2">End Date <span>*</span></label>
                                        </div>
                                        <div class="form__input">
                                            <input class="form-control" name="end_date" id="startingDate2" type="date"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="from__input-box">
                                        <div class="form__input-title">
                                            <label for="description">Description <span>*</span></label>
                                        </div>
                                        <div class="form__input">
                                            <textarea class="form-control" name="description" id="description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="submit__btn d-flex align-items-center justify-content-end gap-10">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ADD Announcement
            document.querySelector('[data-bs-target="#addNewAnnouncement"]').addEventListener("click", function() {
                resetForm();
                document.getElementById("announcementForm").action = "{{ route('announcements.store') }}";
                document.getElementById("formMethod").value = "POST";
                document.querySelector("#addNewAnnouncement .modal-title").innerText =
                    "Add New Announcement";
            });

            // EDIT Announcement (trigger from edit button in table)
            document.querySelectorAll(".edit-announcement").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    let id = this.dataset.id;
                    let title = this.dataset.title;
                    let start = this.dataset.start;
                    let end = this.dataset.end;
                    let description = this.dataset.description;

                    resetForm();
                    document.getElementById("announcementForm").action = "/announcements/" + id;
                    document.getElementById("formMethod").value = "PUT";
                    document.getElementById("announcement_id").value = id;

                    document.getElementById("title").value = title;
                    document.getElementById("startingDate").value = start;
                    document.getElementById("startingDate2").value = end;
                    document.getElementById("description").value = description;

                    document.querySelector("#addNewAnnouncement .modal-title").innerText =
                        "Edit Announcement";
                    new bootstrap.Modal(document.getElementById("addNewAnnouncement")).show();
                });
            });

            function resetForm() {
                document.getElementById("announcementForm").reset();
                document.getElementById("announcement_id").value = "";
            }
        });
    </script>
@endpush
