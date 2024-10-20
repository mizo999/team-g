<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div id="map" style="height:500px"></div>

    <form action="{{ route('result.currentLocation') }}" method="get">
        <input type="hidden" name="lat" value="" class="lat_input">
        <input type="hidden" name="lng" value="" class="lng_input">
        <button type="submit" class="btn btn-success btn-block" disabled>周辺を表示</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- Google Maps API with Places Library -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApsx2TXanoD2FbmzLcCfqajqlEPA__B50&libraries=places&callback=initMap" async defer></script>

    <script>
        // 地図とスーパーマーケットを表示するための初期化関数
        function initMap() {
            // 初期位置（例：東京駅周辺）
            const initialLocation = { lat: 35.681236, lng: 139.767125 };

            // 地図を表示
            const map = new google.maps.Map(document.getElementById("map"), {
                center: initialLocation,
                zoom: 15,
            });

            // 現在地を取得する
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // 現在地にマーカーを表示
                    new google.maps.Marker({
                        position: userLocation,
                        map: map,
                        title: "現在地"
                    });

                    // マップの中心を現在地に設定
                    map.setCenter(userLocation);

                    // 周辺のスーパーマーケットを検索
                    const request = {
                        location: userLocation,
                        radius: '2000',  // 半径2kmに拡大
                        type: ['supermarket']  // スーパーマーケットのみを検索
                    };

                    // Places APIのサービスを利用して周辺検索を行う
                    const service = new google.maps.places.PlacesService(map);
                    service.nearbySearch(request, function(results, status) {
                        if (status === google.maps.places.PlacesServiceStatus.OK) {
                            for (let i = 0; i < results.length; i++) {
                                createMarker(results[i]);
                            }
                        } else {
                            console.error('Places APIのステータス: ' + status);
                        }
                    });
                }, function() {
                    handleLocationError(true, map.getCenter());
                });
            } else {
                // Geolocation APIが使用できない場合のエラーハンドリング
                handleLocationError(false, map.getCenter());
            }

            // スーパーマーケットの位置にマーカーを表示
            function createMarker(place) {
                if (!place.geometry || !place.geometry.location) return;

                new google.maps.Marker({
                    map: map,
                    position: place.geometry.location,
                    title: place.name
                });
            }

            // 位置情報エラー処理
            function handleLocationError(browserHasGeolocation, pos) {
                alert(browserHasGeolocation
                      ? 'エラー: 現在地を取得できません。'
                      : 'エラー: このブラウザではGeolocationがサポートされていません。');
                map.setCenter(pos);
            }
        }
    </script>
</body>
</html>