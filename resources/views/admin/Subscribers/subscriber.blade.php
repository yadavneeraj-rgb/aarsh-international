@extends("admin.layouts.master")
@section("title", "Subscribers | Aarsh International")
@section("content")


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">All Subscribers</h4>
            </div>
            <div class="card-body">
                @if($subscribers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Id</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                                <th>Subscribed Date</th>
                                <th>Last Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $index => $subscriber)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $subscriber->phone_number }}</span>
                                </td>
                                <td>
                                    @if($subscriber->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $subscriber->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    @if($subscriber->last_download_at)
                                        {{ $subscriber->last_download_at->format('d M Y') }}
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No subscribers found</h5>
                    <p class="text-muted">Subscribers will appear here when they sign up for price list downloads.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection