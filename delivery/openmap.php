<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Location</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
    integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
    crossorigin=""/>
    
    <link rel="stylesheet" href="../home.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
        
        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; }

        /* Map Container Styling */
        #map-container {
            width: 90%;
            height: 400px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            z-index: 1;
            border: 2px solid #06C167;
        }

        #contain { text-align: center; padding: 20px; }

        .nav-bar a.active { background: #06C167; color: white; }
        
        /* Location Text Styling */
        #city-name { font-size: 20px; font-weight: bold; color: #06C167; margin-top: 10px; }
        #address { color: #555; font-size: 14px; margin-top: 5px; padding: 0 20px; }
        
        /* Error Message Style */
        .warning-msg { color: orange; font-size: 12px; margin-bottom: 10px; }

        @media screen and (max-width: 600px) {
            #map-container { height: 300px; }
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        <nav class="nav-bar">
            <ul>
                <li><a href="delivery.php">Home</a></li>
                <li><a href="#" class="active">Map</a></li>
                <li><a href="deliverymyord.php">My Orders</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div id="contain">
        <h3>Your Current Location</h3>
        
        <div id="map-container"></div>
        
        <div id="city-name">Locating...</div>
        <div id="address">Please allow location access...</div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
    integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
    crossorigin=""></script>

    <script>
        // Hamburger Menu Logic
        const hamburger = document.querySelector(".hamburger");
        hamburger.onclick = function() {
            const navBar = document.querySelector(".nav-bar");
            navBar.classList.toggle("active");
        }

        // MAP LOGIC
        document.addEventListener("DOMContentLoaded", function() {
            
            // 1. Check if Browser supports Geolocation
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                // Fallback for old browsers
                loadDefaultMap("Geolocation is not supported by this browser.");
            }

            function showPosition(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                loadMap(lat, lng, "You are here");
            }

            function showError(error) {
                let msg = "";
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        msg = "User denied the request for Geolocation.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        msg = "Location information is unavailable.";
                        break;
                    case error.TIMEOUT:
                        msg = "The request to get user location timed out.";
                        break;
                    case error.UNKNOWN_ERROR:
                        msg = "An unknown error occurred.";
                        break;
                }
                // If error, load Default Map (Chennai)
                loadDefaultMap(msg);
            }

            function loadDefaultMap(errorMsg) {
                // Default coordinates (Chennai)
                const defaultLat = 13.0827;
                const defaultLng = 80.2707;
                
                loadMap(defaultLat, defaultLng, "Default Location (Chennai)");
                
                document.getElementById("city-name").innerHTML = "Location Access Denied";
                document.getElementById("address").innerHTML = `<span class="warning-msg">${errorMsg} <br> Showing default map. Enable location to see your spot.</span>`;
            }

            function loadMap(lat, lng, popupText) {
                // Initialize Leaflet Map
                // If map already exists, remove it (prevents errors on reload)
                const container = L.DomUtil.get('map-container');
                if(container != null){
                container._leaflet_id = null;
                }

                const map = L.map('map-container').setView([lat, lng], 15);

                // Add OpenStreetMap Tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Add Marker
                const marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup(`<b>${popupText}</b>`)
                    .openPopup();

                // Only fetch address if it's REAL location (not default)
                if(popupText === "You are here") {
                    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;
                    
                    fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const address = data.address;
                        const city = address.city || address.town || address.village || address.county || "Unknown Location";
                        document.getElementById("city-name").innerHTML = "You are in " + city;
                        document.getElementById("address").innerHTML = data.display_name;
                    })
                    .catch(err => console.error(err));
                }
            }
        });
    </script>
</body>
</html>