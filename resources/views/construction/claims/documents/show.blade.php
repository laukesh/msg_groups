{{-- Documents --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">

        <div class="d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="bi bi-paperclip"></i>
                Documents
            </h5>

            <div class="d-flex gap-2">

                <span class="badge bg-secondary">

                    {{ $claim->documents->count() }}

                </span>

                <a href="{{ route(
                    'admin.projects.construction.claims.documents.create',
                    [
                        'project' => $project,
                        'claim' => $claim,
                    ]
                ) }}"
                   class="btn btn-sm btn-primary">

                    <i class="bi bi-upload"></i>
                    Upload

                </a>

            </div>

        </div>

    </div>


    <div class="card-body">

        @if($claim->documents->count())

            <div class="table-responsive">

                <table class="table table-sm table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>
                                Document
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Uploaded By
                            </th>

                            <th>
                                Date
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($claim->documents as $document)

                            <tr>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $document->document_title }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $document->file_name }}

                                    </small>

                                </td>


                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ $document->document_type }}

                                    </span>

                                </td>


                                <td>

                                    {{ $document->uploadedBy?->name ?? '-' }}

                                </td>


                                <td>

                                    {{ $document->created_at?->format('d M Y') }}

                                </td>


                                <td class="text-end">

                                    <div class="d-flex justify-content-end gap-1">

                                        <a href="{{ route(
                                            'admin.projects.construction.claims.documents.view',
                                            [
                                                'project' => $project,
                                                'claim' => $claim,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary"
                                           title="View">

                                            <i class="bi bi-eye"></i>

                                        </a>


                                        <a href="{{ route(
                                            'admin.projects.construction.claims.documents.download',
                                            [
                                                'project' => $project,
                                                'claim' => $claim,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                           class="btn btn-sm btn-outline-success"
                                           title="Download">

                                            <i class="bi bi-download"></i>

                                        </a>


                                        <a href="{{ route(
                                            'admin.projects.construction.claims.documents.index',
                                            [
                                                'project' => $project,
                                                'claim' => $claim,
                                            ]
                                        ) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="All Documents">

                                            <i class="bi bi-folder"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="text-center py-4">

                <i class="bi bi-file-earmark-text fs-1 text-muted"></i>

                <p class="text-muted mt-2 mb-3">
                    No documents have been uploaded for this claim.
                </p>

                <a href="{{ route(
                    'admin.projects.construction.claims.documents.create',
                    [
                        'project' => $project,
                        'claim' => $claim,
                    ]
                ) }}"
                   class="btn btn-sm btn-primary">

                    <i class="bi bi-upload"></i>
                    Upload Document

                </a>

            </div>

        @endif

    </div>

</div>