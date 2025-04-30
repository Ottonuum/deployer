@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Favorite Pokémon</h1>
        <a href="{{ route('favorite-subjects.create') }}" class="btn btn-primary">Add New Pokémon</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @foreach($subjects as $subject)
            <div class="col-md-4 mb-4">
                <div class="card h-100" data-id="{{ $subject->id }}">
                    <div class="card-img-container" style="height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                        <img src="{{ $subject->image }}" 
                             class="card-img-top" 
                             alt="{{ $subject->title }}"
                             style="max-height: 100%; width: auto; object-fit: contain;">
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $subject->title }}</h5>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p class="card-text">{{ $subject->description }}</p>
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-primary">{{ $subject->element_type }}</span>
                            <span class="badge bg-danger">Power: {{ $subject->power_level }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pokémon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Pokémon Name</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="edit_image" name="image" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_element_type" class="form-label">Element Type</label>
                        <select class="form-select" id="edit_element_type" name="element_type" required>
                            <option value="Fire">Fire</option>
                            <option value="Water">Water</option>
                            <option value="Grass">Grass</option>
                            <option value="Electric">Electric</option>
                            <option value="Psychic">Psychic</option>
                            <option value="Fighting">Fighting</option>
                            <option value="Dark">Dark</option>
                            <option value="Fairy">Fairy</option>
                            <option value="Dragon">Dragon</option>
                            <option value="Steel">Steel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_power_level" class="form-label">Power Level (1-1000)</label>
                        <input type="number" class="form-control" id="edit_power_level" name="power_level" min="1" max="1000" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEdit">Save changes</button>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card:hover {
    transform: translateY(-5px);
}

.card-img-container {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
    font-size: 0.9rem;
    padding: 0.5em 1em;
}

.btn-group {
    opacity: 0;
    transition: opacity 0.2s;
}

.card:hover .btn-group {
    opacity: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this Pokémon?')) {
                const card = this.closest('.card');
                const id = card.dataset.id;
                
                fetch(`/favorite-subjects/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        card.closest('.col-md-4').remove();
                    }
                });
            }
        });
    });

    // Edit functionality
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.card');
            const id = card.dataset.id;
            
            // Fill form with current values
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_title').value = card.querySelector('.card-title').textContent;
            document.getElementById('edit_image').value = card.querySelector('img').src;
            document.getElementById('edit_description').value = card.querySelector('.card-text').textContent;
            document.getElementById('edit_element_type').value = card.querySelector('.badge.bg-primary').textContent;
            document.getElementById('edit_power_level').value = card.querySelector('.badge.bg-danger').textContent.match(/\d+/)[0];
        });
    });

    // Save edit
    document.getElementById('saveEdit').addEventListener('click', function() {
        const form = document.getElementById('editForm');
        const id = document.getElementById('edit_id').value;
        
        fetch(`/favorite-subjects/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                title: document.getElementById('edit_title').value,
                image: document.getElementById('edit_image').value,
                description: document.getElementById('edit_description').value,
                element_type: document.getElementById('edit_element_type').value,
                power_level: document.getElementById('edit_power_level').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });
});
</script>
@endsection 