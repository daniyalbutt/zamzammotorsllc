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
                                <tr>
                                    <td>Annual Company Retreat</td>
                                    <td>Jun 10, 2024</td>
                                    <td>Jun 15, 2024</td>
                                    <td>A week-long retreat for team building and strategy sessions.</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start gap-10">
                                            <button type="button" class="table__icon edit" data-bs-toggle="modal"
                                                data-bs-target="#announcementEdit">
                                                <i class="fa-sharp fa-light fa-pen"></i>
                                            </button>
                                            <button class="removeBtn table__icon delete">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quarterly Business Review</td>
                                    <td>Jul 01, 2024</td>
                                    <td>Jul 02, 2024</td>
                                    <td>Review of business performance for the past quarter.</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start gap-10">
                                            <button type="button" class="table__icon edit" data-bs-toggle="modal"
                                                data-bs-target="#announcementEdit">
                                                <i class="fa-sharp fa-light fa-pen"></i>
                                            </button>
                                            <button class="removeBtn table__icon delete">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Product Launch</td>
                                    <td>Aug 15, 2024</td>
                                    <td>Aug 15, 2024</td>
                                    <td>Official launch event for the new product line.</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start gap-10">
                                            <button type="button" class="table__icon edit" data-bs-toggle="modal"
                                                data-bs-target="#announcementEdit">
                                                <i class="fa-sharp fa-light fa-pen"></i>
                                            </button>
                                            <button class="removeBtn table__icon delete">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Employee Training Program</td>
                                    <td>Sep 05, 2024</td>
                                    <td>Sep 10, 2024</td>
                                    <td>Intensive training sessions for new employees.</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start gap-10">
                                            <button type="button" class="table__icon edit" data-bs-toggle="modal"
                                                data-bs-target="#announcementEdit">
                                                <i class="fa-sharp fa-light fa-pen"></i>
                                            </button>
                                            <button class="removeBtn table__icon delete">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>End of Year Gala</td>
                                    <td>Dec 20, 2024</td>
                                    <td>Dec 20, 2024</td>
                                    <td>Celebration event to close out the year.</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start gap-10">
                                            <button type="button" class="table__icon edit" data-bs-toggle="modal"
                                                data-bs-target="#announcementEdit">
                                                <i class="fa-sharp fa-light fa-pen"></i>
                                            </button>
                                            <button class="removeBtn table__icon delete">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>End of Year Gala</td>
                                    <td>Dec 20, 2024</td>
                                    <td>Dec 20, 2024</td>
                                    <td>Celebration event to close out the year.</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start gap-10">
                                            <button type="button" class="table__icon edit" data-bs-toggle="modal"
                                                data-bs-target="#announcementEdit">
                                                <i class="fa-sharp fa-light fa-pen"></i>
                                            </button>
                                            <button class="removeBtn table__icon delete">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Team Building Workshop</td>
                                    <td>Oct 12, 2024</td>
                                    <td>Oct 13, 2024</td>
                                    <td>Workshop aimed at improving team collaboration and communication skills.
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start gap-10">
                                            <button type="button" class="table__icon edit" data-bs-toggle="modal"
                                                data-bs-target="#announcementEdit">
                                                <i class="fa-sharp fa-light fa-pen"></i>
                                            </button>
                                            <button class="removeBtn table__icon delete">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Client Appreciation Event</td>
                                    <td>Nov 05, 2024</td>
                                    <td>Nov 05, 2024</td>
                                    <td>Event to show appreciation for our valued clients.</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start gap-10">
                                            <button type="button" class="table__icon edit" data-bs-toggle="modal"
                                                data-bs-target="#announcementEdit">
                                                <i class="fa-sharp fa-light fa-pen"></i>
                                            </button>
                                            <button class="removeBtn table__icon delete">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mid-Year Performance Review</td>
                                    <td>Jul 15, 2024</td>
                                    <td>Jul 16, 2024</td>
                                    <td>Review of employee performance for the first half of the year.</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start gap-10">
                                            <button type="button" class="table__icon edit" data-bs-toggle="modal"
                                                data-bs-target="#announcementEdit">
                                                <i class="fa-sharp fa-light fa-pen"></i>
                                            </button>
                                            <button class="removeBtn table__icon delete">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Health and Wellness Fair</td>
                                    <td>Sep 20, 2024</td>
                                    <td>Sep 20, 2024</td>
                                    <td>An event focused on promoting health and wellness among employees.</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start gap-10">
                                            <button type="button" class="table__icon edit" data-bs-toggle="modal"
                                                data-bs-target="#announcementEdit">
                                                <i class="fa-sharp fa-light fa-pen"></i>
                                            </button>
                                            <button class="removeBtn table__icon delete">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Technology Update Seminar</td>
                                    <td>Aug 22, 2024</td>
                                    <td>Aug 22, 2024</td>
                                    <td>Seminar to discuss the latest updates and trends in technology.</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start gap-10">
                                            <button type="button" class="table__icon edit" data-bs-toggle="modal"
                                                data-bs-target="#announcementEdit">
                                                <i class="fa-sharp fa-light fa-pen"></i>
                                            </button>
                                            <button class="removeBtn table__icon delete">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
