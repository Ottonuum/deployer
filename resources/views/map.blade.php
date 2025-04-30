<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Map</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .map-container {
            flex: 3;
            height: 100%;
            position: relative;
        }
        .sidebar {
            flex: 1;
            background-color: #f5f5f5;
            padding: 20px;
            overflow-y: auto;
        }
        h1, h2 {
            color: #333;
            margin-top: 0;
        }
        #map {
            height: 100%;
            width: 100%;
        }
        .marker-form {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .marker-form input, .marker-form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .marker-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
        }
        .marker-list {
            list-style: none;
            padding: 0;
        }
        .marker-item {
            background-color: white;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .marker-actions {
            margin-top: 10px;
            display: flex;
            gap: 5px;
        }
        .btn-edit {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn-cancel {
            background-color: #9e9e9e;
            color: white;
            border: none;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 4px;
        }
        .hidden {
            display: none;
        }
        .info-box {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 60px;
            z-index: 1000;
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #333;
        }
        .back-button:hover {
            background-color: rgba(255, 255, 255, 1);
        }
        .back-button i {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="map-container">
            <button class="back-button" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
                Back
            </button>
            <div id="map"></div>
        </div>
        <div class="sidebar">
            <h2>Markers</h2>
            <div class="marker-form" id="add-marker-form">
                <h3>Add Marker</h3>
                <form id="markerForm">
                    <input type="text" id="name" placeholder="Name" required>
                    <input type="number" id="latitude" placeholder="Latitude" step="any" required>
                    <input type="number" id="longitude" placeholder="Longitude" step="any" required>
                    <textarea id="description" placeholder="Description"></textarea>
                    <button type="submit">Add Marker</button>
                </form>
            </div>
            
            <div class="marker-form hidden" id="edit-marker-form">
                <h3>Edit Marker</h3>
                <form id="editForm">
                    <input type="hidden" id="edit-id">
                    <input type="text" id="edit-name" placeholder="Name" required>
                    <input type="number" id="edit-latitude" placeholder="Latitude" step="any" required>
                    <input type="number" id="edit-longitude" placeholder="Longitude" step="any" required>
                    <textarea id="edit-description" placeholder="Description"></textarea>
                    <div class="marker-actions">
                        <button type="submit" class="btn-edit">Update</button>
                        <button type="button" class="btn-cancel" id="cancelEdit">Cancel</button>
                    </div>
                </form>
            </div>
            
            <h3>All Markers</h3>
            <ul class="marker-list" id="markerList">
                <!-- Markers will be listed here dynamically -->
            </ul>
        </div>
    </div>

    <!-- OpenLayers for map display -->
    <script src="https://cdn.jsdelivr.net/npm/ol@v7.4.0/dist/ol.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.4.0/ol.css">
    
    <script>
        // CSRF token setup for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Map initialization
        const vectorSource = new ol.source.Vector();
        
        const vectorLayer = new ol.layer.Vector({
            source: vectorSource,
            style: new ol.style.Style({
                image: new ol.style.Circle({
                    radius: 6,
                    fill: new ol.style.Fill({
                        color: '#3399CC'
                    }),
                    stroke: new ol.style.Stroke({
                        color: '#fff',
                        width: 2
                    })
                })
            })
        });
        
        const map = new ol.Map({
            target: 'map',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                }),
                vectorLayer
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([0, 0]),
                zoom: 2
            })
        });
        
        // Click handler to get coordinates
        map.on('click', function(evt) {
            const coordinates = ol.proj.toLonLat(evt.coordinate);
            document.getElementById('longitude').value = coordinates[0].toFixed(7);
            document.getElementById('latitude').value = coordinates[1].toFixed(7);
        });
        
        // Load existing markers
        function loadMarkers() {
            fetch('/api/markers')
                .then(response => response.json())
                .then(markers => {
                    // Clear existing markers
                    vectorSource.clear();
                    
                    // Clear the marker list
                    const markerList = document.getElementById('markerList');
                    markerList.innerHTML = '';
                    
                    // Add markers to the map and list
                    markers.forEach(marker => {
                        addMarkerToMap(marker);
                        addMarkerToList(marker);
                    });
                })
                .catch(error => console.error('Error loading markers:', error));
        }
        
        // Add a marker to the map
        function addMarkerToMap(marker) {
            const feature = new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat([marker.longitude, marker.latitude])),
                id: marker.id,
                name: marker.name,
                description: marker.description
            });
            
            vectorSource.addFeature(feature);
            return feature;
        }
        
        // Add a marker to the list
        function addMarkerToList(marker) {
            const markerList = document.getElementById('markerList');
            const li = document.createElement('li');
            li.className = 'marker-item';
            li.setAttribute('data-id', marker.id);
            
            li.innerHTML = `
                <h4>${marker.name}</h4>
                <p><strong>Coordinates:</strong> ${marker.latitude}, ${marker.longitude}</p>
                <p>${marker.description || 'No description'}</p>
                <div class="marker-actions">
                    <button class="btn-edit" onclick="editMarker(${marker.id})">Edit</button>
                    <button class="btn-delete" onclick="deleteMarker(${marker.id})">Delete</button>
                </div>
            `;
            
            markerList.appendChild(li);
        }
        
        // Add a new marker
        document.getElementById('markerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                name: document.getElementById('name').value,
                latitude: parseFloat(document.getElementById('latitude').value),
                longitude: parseFloat(document.getElementById('longitude').value),
                description: document.getElementById('description').value
            };
            
            fetch('/api/markers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(marker => {
                // Reset form
                document.getElementById('markerForm').reset();
                
                // Add marker to map and list
                addMarkerToMap(marker);
                addMarkerToList(marker);
            })
            .catch(error => console.error('Error adding marker:', error));
        });
        
        // Edit marker form toggle
        function editMarker(id) {
            fetch(`/api/markers/${id}`)
                .then(response => response.json())
                .then(marker => {
                    // Show edit form and hide add form
                    document.getElementById('add-marker-form').classList.add('hidden');
                    document.getElementById('edit-marker-form').classList.remove('hidden');
                    
                    // Fill edit form
                    document.getElementById('edit-id').value = marker.id;
                    document.getElementById('edit-name').value = marker.name;
                    document.getElementById('edit-latitude').value = marker.latitude;
                    document.getElementById('edit-longitude').value = marker.longitude;
                    document.getElementById('edit-description').value = marker.description || '';
                })
                .catch(error => console.error('Error fetching marker:', error));
        }
        
        // Cancel edit
        document.getElementById('cancelEdit').addEventListener('click', function() {
            document.getElementById('add-marker-form').classList.remove('hidden');
            document.getElementById('edit-marker-form').classList.add('hidden');
            document.getElementById('editForm').reset();
        });
        
        // Update marker
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('edit-id').value;
            const formData = {
                name: document.getElementById('edit-name').value,
                latitude: parseFloat(document.getElementById('edit-latitude').value),
                longitude: parseFloat(document.getElementById('edit-longitude').value),
                description: document.getElementById('edit-description').value
            };
            
            fetch(`/api/markers/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(() => {
                // Reset and hide edit form, show add form
                document.getElementById('editForm').reset();
                document.getElementById('add-marker-form').classList.remove('hidden');
                document.getElementById('edit-marker-form').classList.add('hidden');
                
                // Reload markers
                loadMarkers();
            })
            .catch(error => console.error('Error updating marker:', error));
        });
        
        // Delete marker
        function deleteMarker(id) {
            if (confirm('Are you sure you want to delete this marker?')) {
                fetch(`/api/markers/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(() => loadMarkers())
                .catch(error => console.error('Error deleting marker:', error));
            }
        }
        
        // Initialize by loading existing markers
        window.editMarker = editMarker;
        window.deleteMarker = deleteMarker;
        document.addEventListener('DOMContentLoaded', loadMarkers);
        
        // Load initial markers from the server
        @if(isset($markers) && count($markers) > 0)
            const initialMarkers = @json($markers);
            initialMarkers.forEach(marker => {
                addMarkerToMap(marker);
                addMarkerToList(marker);
            });
        @endif
    </script>
</body>
</html>