<div class="card__wrapper">
    <div class="card__title-wrap d-flex align-items-center justify-content-between mb-20">
        <h5 class="card__heading-title">Announcements</h5>

    </div>
    <div class="table-height-2 card__scroll">
        <div class="table__wrapper meeting-table table-responsive">
            <table class="table mb-20">
                <thead>
                    <tr class="table__title">
                        <th>Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (DB::table('annoucements')->get() as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->start_date }}</td>
                            <td>{{ $item->end_date }}</td>
                            <td>{{ $item->description }}</td>
                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
    </div>

</div>
