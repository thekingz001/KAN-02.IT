<!DOCTYPE html>
<html>
  <head>
    <title>Locator</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/handlebars/4.7.7/handlebars.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style>
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }

      #map-container {
        width: 100%;
        height: 100%;
        position: relative;
        font-family: "Roboto", sans-serif;
        box-sizing: border-box;
      }

      #map-container button {
        background: none;
        color: inherit;
        border: none;
        padding: 0;
        font: inherit;
        font-size: inherit;
        cursor: pointer;
      }

      #map {
        position: absolute;
        left: 22em;
        top: 0;
        right: 0;
        bottom: 0;
      }

      #locations-panel {
        position: absolute;
        left: 0;
        width: 22em;
        top: 0;
        bottom: 0;
        overflow-y: auto;
        background: white;
        padding: 0.5em;
        box-sizing: border-box;
      }

      @media only screen and (max-width: 876px) {
        #map {
          left: 0;
          bottom: 50%;
        }

        #locations-panel {
          top: 50%;
          right: 0;
          width: unset;
        }
      }

      #locations-panel-list > header {
        padding: 1.4em 1.4em 0 1.4em;
      }

      #locations-panel-list h1.search-title {
        font-size: 1em;
        font-weight: 500;
        margin: 0;
      }

      #locations-panel-list h1.search-title > img {
        vertical-align: bottom;
        margin-top: -1em;
      }

      #locations-panel-list .search-input {
        width: 100%;
        margin-top: 0.8em;
        position: relative;
      }

      #locations-panel-list .search-input input {
        width: 100%;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 0.3em;
        height: 2.2em;
        box-sizing: border-box;
        padding: 0 2.5em 0 1em;
        font-size: 1em;
      }

      #locations-panel-list .search-input-overlay {
        position: absolute;
      }

      #locations-panel-list .search-input-overlay.search {
        right: 2px;
        top: 2px;
        bottom: 2px;
        width: 2.4em;
      }

      #locations-panel-list .search-input-overlay.search button {
        width: 100%;
        height: 100%;
        border-radius: 0.2em;
        color: black;
        background: transparent;
      }

      #locations-panel-list .search-input-overlay.search .icon {
        margin-top: 0.05em;
        vertical-align: top;
      }

      #locations-panel-list .section-name {
        font-weight: 500;
        font-size: 0.9em;
        margin: 1.8em 0 1em 1.5em;
      }

      #locations-panel-list .location-result {
        position: relative;
        padding: 0.8em 3.5em 0.8em 1.4em;
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        cursor: pointer;
      }

      #locations-panel-list .location-result:first-of-type {
        border-top: 1px solid rgba(0, 0, 0, 0.12);
      }

      #locations-panel-list .location-result:last-of-type {
        border-bottom: none;
      }

      #locations-panel-list .location-result.selected {
        outline: 2px solid #4285f4;
      }

      #locations-panel-list button.select-location {
        margin-bottom: 0.6em;
        text-align: left;
      }

      #locations-panel-list .location-result h2.name {
        font-size: 1em;
        font-weight: 500;
        margin: 0;
      }

      #locations-panel-list .location-result .address {
        font-size: 0.9em;
        margin-bottom: 0.5em;
      }

      #locations-panel-list .location-result .details-button {
        font-size: 0.9em;
        color: #7e7efd;
      }

      #locations-panel-list .location-result .distance {
        position: absolute;
        top: 0.9em;
        right: 0;
        text-align: center;
        font-size: 0.9em;
        width: 5em;
      }

      #locations-panel-list .show-directions {
        position: absolute;
        right: 1.2em;
        top: 2.3em;
      }

      #location-results-list {
        list-style-type: none;
        margin: 0;
        padding: 0;
      }

      /* ------------- DETAILS PANEL ------------------------------- */
      #locations-panel-details {
        padding: 1.4em;
        box-sizing: border-box;
        display: none;
      }

      #locations-panel-details .back-button {
        font-size: 1em;
        font-weight: 500;
        color: #7e7efd;
        display: block;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font-family: inherit;
      }

      #locations-panel-details .back-button .icon {
        width: 20px;
        height: 20px;
        vertical-align: bottom;

        /* Match link color #7e7efd */
        filter: invert(65%) sepia(87%) saturate(4695%) hue-rotate(217deg) brightness(105%) contrast(98%);
      }

      #locations-panel-details > header {
        text-align: center;
      }

      #locations-panel-details .banner {
        margin-top: 1em;
      }

      #locations-panel-details h2 {
        font-size: 1.1em;
        font-weight: 500;
        margin-bottom: 0.3em;
      }

      #locations-panel-details .distance {
        font-size: 0.9em;
        text-align: center;
      }

      #locations-panel-details .address {
        text-align: center;
        font-size: 0.9em;
        margin-top: 1.3em;
      }

      #locations-panel-details .atmosphere {
        text-align: center;
        font-size: 0.9em;
        margin: 0.8em 0;
      }

      #locations-panel-details .star-rating-numeric {
        color: #555;
      }

      #locations-panel-details .star-icon {
        width: 1.2em;
        height: 1.2em;
        margin-right: -0.3em;
        margin-top: -0.08em;
        vertical-align: top;
        filter: invert(88%) sepia(60%) saturate(2073%) hue-rotate(318deg) brightness(93%) contrast(104%);
      }

      #locations-panel-details .star-icon:last-of-type {
        margin-right: 0.2em;
      }

      #locations-panel-details .price-dollars {
        color: #555;
      }

      #locations-panel-details hr {
        height: 1px;
        color: rgba(0, 0, 0, 0.12);
        background-color: rgba(0, 0, 0, 0.12);
        border: none;
        margin-bottom: 1em;
      }

      #locations-panel-details .contact {
        font-size: 0.9em;
        margin: 0.8em 0;
        display: flex;
        align-items: center;
      }

      #locations-panel-details .contact .icon {
        flex: 0 0 auto;
        width: 1.5em;
        height: 1.5em;
      }

      #locations-panel-details .contact .right {
        padding: 0.1em 0 0 1em;
      }

      #locations-panel-details a {
        text-decoration: none;
        color: #7e7efd;
      }

      #locations-panel-details .hours .weekday {
        display: inline-block;
        width: 5em;
      }

      #locations-panel-details .website a {
        white-space: nowrap;
        display: inline-block;
        overflow: hidden;
        max-width: 16em;
        text-overflow: ellipsis;
      }

      #locations-panel-details p.attribution {
        color: #777;
        margin: 0;
        font-size: 0.8em;
        font-style: italic;
      }
    </style>
    <script>
      'use strict';

      /** Hide a DOM element. */
      function hideElement(el) {
        el.style.display = 'none';
      }

      /** Show a DOM element that has been hidden. */
      function showElement(el) {
        el.style.display = 'block';
      }

      /**
       * Defines an instance of the Locator+ solution, to be instantiated
       * when the Maps library is loaded.
       */
      function LocatorPlus(configuration) {
        const locator = this;

        locator.locations = configuration.locations || [];
        locator.capabilities = configuration.capabilities || {};

        const mapEl = document.getElementById('map');
        const panelEl = document.getElementById('locations-panel');
        locator.panelListEl = document.getElementById('locations-panel-list');
        const sectionNameEl =
            document.getElementById('location-results-section-name');
        const resultsContainerEl = document.getElementById('location-results-list');

        const itemsTemplate = Handlebars.compile(
            document.getElementById('locator-result-items-tmpl').innerHTML);

        locator.searchLocation = null;
        locator.searchLocationMarker = null;
        locator.selectedLocationIdx = null;
        locator.userCountry = null;

        // Initialize the map -------------------------------------------------------
        locator.map = new google.maps.Map(mapEl, configuration.mapOptions);

        // Store selection.
        const selectResultItem = function(locationIdx, panToMarker, scrollToResult) {
          locator.selectedLocationIdx = locationIdx;
          for (let locationElem of resultsContainerEl.children) {
            locationElem.classList.remove('selected');
            if (getResultIndex(locationElem) === locator.selectedLocationIdx) {
              locationElem.classList.add('selected');
              if (scrollToResult) {
                panelEl.scrollTop = locationElem.offsetTop;
              }
            }
          }
          if (panToMarker && (locationIdx != null)) {
            locator.map.panTo(locator.locations[locationIdx].coords);
          }
        };

        // Create a marker for each location.
        const markers = locator.locations.map(function(location, index) {
          const marker = new google.maps.Marker({
            position: location.coords,
            map: locator.map,
            title: location.title,
          });
          marker.addListener('click', function() {
            selectResultItem(index, false, true);
          });
          return marker;
        });

        // Fit map to marker bounds.
        locator.updateBounds = function() {
          const bounds = new google.maps.LatLngBounds();
          if (locator.searchLocationMarker) {
            bounds.extend(locator.searchLocationMarker.getPosition());
          }
          for (let i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
          }
          locator.map.fitBounds(bounds);
        };
        if (locator.locations.length) {
          locator.updateBounds();
        }

        // Get the distance of a store location to the user's location,
        // used in sorting the list.
        const getLocationDistance = function(location) {
          if (!locator.searchLocation) return null;

          // Use travel distance if available (from Distance Matrix).
          if (location.travelDistanceValue != null) {
            return location.travelDistanceValue;
          }

          // Fall back to straight-line distance.
          return google.maps.geometry.spherical.computeDistanceBetween(
              new google.maps.LatLng(location.coords),
              locator.searchLocation.location);
        };

        // Render the results list --------------------------------------------------
        const getResultIndex = function(elem) {
          return parseInt(elem.getAttribute('data-location-index'));
        };

        locator.renderResultsList = function() {
          let locations = locator.locations.slice();
          for (let i = 0; i < locations.length; i++) {
            locations[i].index = i;
          }
          if (locator.searchLocation) {
            sectionNameEl.textContent =
                'Nearest locations (' + locations.length + ')';
            locations.sort(function(a, b) {
              return getLocationDistance(a) - getLocationDistance(b);
            });
          } else {
            sectionNameEl.textContent = `All locations (${locations.length})`;
          }
          const resultItemContext = {
            locations: locations,
            showDirectionsButton: !!locator.searchLocation
          };
          resultsContainerEl.innerHTML = itemsTemplate(resultItemContext);
          for (let item of resultsContainerEl.children) {
            const resultIndex = getResultIndex(item);
            if (resultIndex === locator.selectedLocationIdx) {
              item.classList.add('selected');
            }

            const resultSelectionHandler = function() {
              if (resultIndex !== locator.selectedLocationIdx) {
                locator.clearDirections();
              }
              selectResultItem(resultIndex, true, false);
            };

            // Clicking anywhere on the item selects this location.
            // Additionally, create a button element to make this behavior
            // accessible under tab navigation.
            item.addEventListener('click', resultSelectionHandler);
            item.querySelector('.select-location')
                .addEventListener('click', function(e) {
                  resultSelectionHandler();
                  e.stopPropagation();
                });

            item.querySelector('.details-button')
                .addEventListener('click', function() {
                  locator.showDetails(resultIndex);
                });

            if (resultItemContext.showDirectionsButton) {
              item.querySelector('.show-directions')
                  .addEventListener('click', function(e) {
                    selectResultItem(resultIndex, false, false);
                    locator.updateDirections();
                    e.stopPropagation();
                  });
            }
          }
        };

        // Optional capability initialization --------------------------------------
        initializeSearchInput(locator);
        initializeDistanceMatrix(locator);
        initializeDirections(locator);
        initializeDetails(locator);

        // Initial render of results -----------------------------------------------
        locator.renderResultsList();
      }

      /** When the search input capability is enabled, initialize it. */
      function initializeSearchInput(locator) {
        const geocodeCache = new Map();
        const geocoder = new google.maps.Geocoder();

        const searchInputEl = document.getElementById('location-search-input');
        const searchButtonEl = document.getElementById('location-search-button');

        const updateSearchLocation = function(address, location) {
          if (locator.searchLocationMarker) {
            locator.searchLocationMarker.setMap(null);
          }
          if (!location) {
            locator.searchLocation = null;
            return;
          }
          locator.searchLocation = {'address': address, 'location': location};
          locator.searchLocationMarker = new google.maps.Marker({
            position: location,
            map: locator.map,
            title: 'My location',
            icon: {
              path: google.maps.SymbolPath.CIRCLE,
              scale: 12,
              fillColor: '#3367D6',
              fillOpacity: 0.5,
              strokeOpacity: 0,
            }
          });

          // Update the locator's idea of the user's country, used for units. Use
          // `formatted_address` instead of the more structured `address_components`
          // to avoid an additional billed call.
          const addressParts = address.split(' ');
          locator.userCountry = addressParts[addressParts.length - 1];

          // Update map bounds to include the new location marker.
          locator.updateBounds();

          // Update the result list so we can sort it by proximity.
          locator.renderResultsList();

          locator.updateTravelTimes();

          locator.clearDirections();
        };

        const geocodeSearch = function(query) {
          if (!query) {
            return;
          }

          const handleResult = function(geocodeResult) {
            searchInputEl.value = geocodeResult.formatted_address;
            updateSearchLocation(
                geocodeResult.formatted_address, geocodeResult.geometry.location);
          };

          if (geocodeCache.has(query)) {
            handleResult(geocodeCache.get(query));
            return;
          }
          const request = {address: query, bounds: locator.map.getBounds()};
          geocoder.geocode(request, function(results, status) {
            if (status === 'OK') {
              if (results.length > 0) {
                const result = results[0];
                geocodeCache.set(query, result);
                handleResult(result);
              }
            }
          });
        };

        // Set up geocoding on the search input.
        searchButtonEl.addEventListener('click', function() {
          geocodeSearch(searchInputEl.value.trim());
        });

        // Initialize Autocomplete.
        initializeSearchInputAutocomplete(
            locator, searchInputEl, geocodeSearch, updateSearchLocation);
      }

      /** Add Autocomplete to the search input. */
      function initializeSearchInputAutocomplete(
          locator, searchInputEl, fallbackSearch, searchLocationUpdater) {
        // Set up Autocomplete on the search input. Bias results to map viewport.
        const autocomplete = new google.maps.places.Autocomplete(searchInputEl, {
          types: ['geocode'],
          fields: ['place_id', 'formatted_address', 'geometry.location']
        });
        autocomplete.bindTo('bounds', locator.map);
        autocomplete.addListener('place_changed', function() {
          const placeResult = autocomplete.getPlace();
          if (!placeResult.geometry) {
            // Hitting 'Enter' without selecting a suggestion will result in a
            // placeResult with only the text input value as the 'name' field.
            fallbackSearch(placeResult.name);
            return;
          }
          searchLocationUpdater(
              placeResult.formatted_address, placeResult.geometry.location);
        });
      }

      /** Initialize Distance Matrix for the locator. */
      function initializeDistanceMatrix(locator) {
        const distanceMatrixService = new google.maps.DistanceMatrixService();

        // Annotate travel times to the selected location using Distance Matrix.
        locator.updateTravelTimes = function() {
          if (!locator.searchLocation) return;

          const units = (locator.userCountry === 'USA') ?
              google.maps.UnitSystem.IMPERIAL :
              google.maps.UnitSystem.METRIC;
          const request = {
            origins: [locator.searchLocation.location],
            destinations: locator.locations.map(function(x) {
              return x.coords;
            }),
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: units,
          };
          const callback = function(response, status) {
            if (status === 'OK') {
              const distances = response.rows[0].elements;
              for (let i = 0; i < distances.length; i++) {
                const distResult = distances[i];
                let travelDistanceText, travelDistanceValue;
                if (distResult.status === 'OK') {
                  travelDistanceText = distResult.distance.text;
                  travelDistanceValue = distResult.distance.value;
                }
                const location = locator.locations[i];
                location.travelDistanceText = travelDistanceText;
                location.travelDistanceValue = travelDistanceValue;
              }

              // Re-render the results list, in case the ordering has changed.
              locator.renderResultsList();
            }
          };
          distanceMatrixService.getDistanceMatrix(request, callback);
        };
      }

      /** Initialize Directions service for the locator. */
      function initializeDirections(locator) {
        const directionsCache = new Map();
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
          suppressMarkers: true,
        });

        // Update directions displayed from the search location to
        // the selected location on the map.
        locator.updateDirections = function() {
          if (!locator.searchLocation || (locator.selectedLocationIdx == null)) {
            return;
          }
          const cacheKey = JSON.stringify(
              [locator.searchLocation.location, locator.selectedLocationIdx]);
          if (directionsCache.has(cacheKey)) {
            const directions = directionsCache.get(cacheKey);
            directionsRenderer.setMap(locator.map);
            directionsRenderer.setDirections(directions);
            return;
          }
          const request = {
            origin: locator.searchLocation.location,
            destination: locator.locations[locator.selectedLocationIdx].coords,
            travelMode: google.maps.TravelMode.DRIVING
          };
          directionsService.route(request, function(response, status) {
            if (status === 'OK') {
              console.log(JSON.stringify(response));
              directionsRenderer.setMap(locator.map);
              directionsRenderer.setDirections(response);
              directionsCache.set(cacheKey, response);
            }
          });
        };

        locator.clearDirections = function() {
          directionsRenderer.setMap(null);
        };
      }

      /** Initialize Place Details service and UI for the locator. */
      function initializeDetails(locator) {
        const panelDetailsEl = document.getElementById('locations-panel-details');
        const detailsService = new google.maps.places.PlacesService(locator.map);

        const detailsTemplate = Handlebars.compile(
            document.getElementById('locator-details-tmpl').innerHTML);

        const renderDetails = function(context) {
          panelDetailsEl.innerHTML = detailsTemplate(context);
          panelDetailsEl.querySelector('.back-button')
              .addEventListener('click', hideDetails);
        };

        const hideDetails = function() {
          showElement(locator.panelListEl);
          hideElement(panelDetailsEl);
        };

        locator.showDetails = function(locationIndex) {
          const location = locator.locations[locationIndex];
          const context = {location};

          // Helper function to create a fixed-size array.
          const initArray = function(arraySize) {
            const array = [];
            while (array.length < arraySize) {
              array.push(0);
            }
            return array;
          };

          if (location.placeId) {
            const request = {
              placeId: location.placeId,
              fields: [
                'formatted_phone_number', 'website', 'opening_hours', 'url',
                'utc_offset_minutes', 'price_level', 'rating', 'user_ratings_total'
              ]
            };
            detailsService.getDetails(request, function(place, status) {
              if (status == google.maps.places.PlacesServiceStatus.OK) {
                if (place.opening_hours) {
                  const daysHours =
                      place.opening_hours.weekday_text.map(e => e.split(/\:\s+/))
                          .map(e => ({'days': e[0].substr(0, 3), 'hours': e[1]}));

                  for (let i = 1; i < daysHours.length; i++) {
                    if (daysHours[i - 1].hours === daysHours[i].hours) {
                      if (daysHours[i - 1].days.indexOf('-') !== -1) {
                        daysHours[i - 1].days =
                            daysHours[i - 1].days.replace(/\w+$/, daysHours[i].days);
                      } else {
                        daysHours[i - 1].days += ' - ' + daysHours[i].days;
                      }
                      daysHours.splice(i--, 1);
                    }
                  }
                  place.openingHoursSummary = daysHours;
                }
                if (place.rating) {
                  const starsOutOfTen = Math.round(2 * place.rating);
                  const fullStars = Math.floor(starsOutOfTen / 2);
                  const halfStars = fullStars !== starsOutOfTen / 2 ? 1 : 0;
                  const emptyStars = 5 - fullStars - halfStars;

                  // Express stars as arrays to make iterating in Handlebars easy.
                  place.fullStarIcons = initArray(fullStars);
                  place.halfStarIcons = initArray(halfStars);
                  place.emptyStarIcons = initArray(emptyStars);
                }
                if (place.price_level) {
                  place.dollarSigns = initArray(place.price_level);
                }
                if (place.website) {
                  const url = new URL(place.website);
                  place.websiteDomain = url.hostname;
                }

                context.place = place;
                renderDetails(context);
              }
            });
          }
          renderDetails(context);
          hideElement(locator.panelListEl);
          showElement(panelDetailsEl);
        };
      }
    </script>
    <script>
      const CONFIGURATION = {
        "locations": [
          {"title":"กรุงเทพมหานคร","address1":"กรุงเทพมหานคร ประเทศไทย","coords":{"lat":13.75654681181717,"lng":100.5018562951065},"placeId":"ChIJ82ENKDJgHTERIEjiXbIAAQE"}
        ],
        "mapOptions": {"center":{"lat":38.0,"lng":-100.0},"fullscreenControl":true,"mapTypeControl":false,"streetViewControl":false,"zoom":4,"zoomControl":true,"maxZoom":17},
        "mapsApiKey": "AIzaSyD_Nve6IbnUVnldOf8qFM1i5YI904clpNk",
        "capabilities": {"input":true,"autocomplete":true,"directions":true,"distanceMatrix":true,"details":true}
      };

      function initMap() {
        new LocatorPlus(CONFIGURATION);
      }
    </script>
    <script id="locator-result-items-tmpl" type="text/x-handlebars-template">
      {{#each locations}}
        <li class="location-result" data-location-index="{{index}}">
          <button class="select-location">
            <h2 class="name">{{title}}</h2>
          </button>
          <div class="address">{{address1}}<br>{{address2}}</div>
          <button class="details-button">
            View details
          </button>
          {{#if travelDistanceText}}
            <div class="distance">{{travelDistanceText}}</div>
          {{/if}}
          {{#if ../showDirectionsButton}}
            <button class="show-directions" title="Show directions to this location">
              <svg width="34" height="34" viewBox="0 0 34 34"
                   fill="none" xmlns="http://www.w3.org/2000/svg" alt="Show directions">
                <path d="M17.5867 9.24375L17.9403 8.8902V8.8902L17.5867 9.24375ZM16.4117 9.24375L16.7653 9.59731L16.7675 9.59502L16.4117 9.24375ZM8.91172 16.7437L8.55817 16.3902L8.91172 16.7437ZM8.91172 17.9229L8.55817 18.2765L8.55826 18.2766L8.91172 17.9229ZM16.4117 25.4187H16.9117V25.2116L16.7652 25.0651L16.4117 25.4187ZM16.4117 25.4229H15.9117V25.63L16.0582 25.7765L16.4117 25.4229ZM25.0909 17.9229L25.4444 18.2765L25.4467 18.2742L25.0909 17.9229ZM25.4403 16.3902L17.9403 8.8902L17.2332 9.5973L24.7332 17.0973L25.4403 16.3902ZM17.9403 8.8902C17.4213 8.3712 16.5737 8.3679 16.0559 8.89248L16.7675 9.59502C16.8914 9.4696 17.1022 9.4663 17.2332 9.5973L17.9403 8.8902ZM16.0582 8.8902L8.55817 16.3902L9.26527 17.0973L16.7653 9.5973L16.0582 8.8902ZM8.55817 16.3902C8.0379 16.9105 8.0379 17.7562 8.55817 18.2765L9.26527 17.5694C9.13553 17.4396 9.13553 17.227 9.26527 17.0973L8.55817 16.3902ZM8.55826 18.2766L16.0583 25.7724L16.7652 25.0651L9.26517 17.5693L8.55826 18.2766ZM15.9117 25.4187V25.4229H16.9117V25.4187H15.9117ZM16.0582 25.7765C16.5784 26.2967 17.4242 26.2967 17.9444 25.7765L17.2373 25.0694C17.1076 25.1991 16.895 25.1991 16.7653 25.0694L16.0582 25.7765ZM17.9444 25.7765L25.4444 18.2765L24.7373 17.5694L17.2373 25.0694L17.9444 25.7765ZM25.4467 18.2742C25.9631 17.7512 25.9663 16.9096 25.438 16.3879L24.7354 17.0995C24.8655 17.2279 24.8687 17.4363 24.7351 17.5716L25.4467 18.2742Z" fill="#7e7efd"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M19 19.8333V17.75H15.6667V20.25H14V16.9167C14 16.4542 14.3708 16.0833 14.8333 16.0833H19V14L21.9167 16.9167L19 19.8333Z" fill="#7e7efd"/>
                <circle cx="17" cy="17" r="16.5" stroke="#7e7efd"/>
              </svg>
            </button>
          {{/if}}
        </li>
      {{/each}}
    </script>
  </head>
  <body>
    <div id="map-container">
      <div id="locations-panel">
        <div id="locations-panel-list">
          <header>
            <h1 class="search-title">
              <img src="https://fonts.gstatic.com/s/i/googlematerialicons/place/v15/24px.svg"/>
              Find a location near you
            </h1>
            <div class="search-input">
              <input id="location-search-input" placeholder="Enter your address or zip code">
              <div id="search-overlay-search" class="search-input-overlay search">
                <button id="location-search-button">
                  <img class="icon" src="https://fonts.gstatic.com/s/i/googlematerialicons/search/v11/24px.svg" alt="Search"/>
                </button>
              </div>
            </div>
          </header>
          <div class="section-name" id="location-results-section-name">
            All locations
          </div>
          <div class="results">
            <ul id="location-results-list"></ul>
          </div>
        </div>
        <div id="locations-panel-details"></div>
      </div>
      <div id="map"></div>
    </div>
  </body>
</html>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_Nve6IbnUVnldOf8qFM1i5YI904clpNk&callback=initMap&libraries=places,geometry&solution_channel=GMP_QB_locatorplus_v4_cABCDE" async defer></script>
