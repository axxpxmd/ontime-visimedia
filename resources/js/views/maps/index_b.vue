<template>
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="card shadow h-80">
          <div class="card-header">
            <h5 class="m-0 pt-1 font-weight-bold float-left">Lokasi Pegawai</h5>
            <div class="text-right">
              <button
                class="btn btn-info btn-sm"
                :disabled="isLoading"
                @click="reload()"
              >
                Refresh
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="container_map" style="height: 500px; width: 100%">
              <l-map
                ref="map"
                style="height: 500px"
                :zoom="zoom"
                :center="center"
              >
                <l-tile-layer
                  :url="url"
                  :attribution="attribution"
                ></l-tile-layer>
                <l-marker
                  v-for="marker in usermaps"
                  :visible="marker.active"
                  :ref="'marker-' + marker.id"
                  :lat-lng="[marker.lat, marker.long]"
                  :key="marker.id"
                  @ready="openDefaultMarkers($event, marker)"
                >
                  <l-popup ref="popup">
                    <div>
                      <center>
                        {{ marker.user.nama }}
                        <br />
                        <small>{{ marker.user.unitkerjas.unit_kerja }}</small>
                        <br />
                        <small
                          >Last update : {{ marker.updated_at }} ({{
                            marker.device
                          }})</small
                        >
                      </center>
                    </div>
                  </l-popup>
                </l-marker>
                <!-- <l-marker :lat-lng="markerLatLng"> </l-marker> -->
              </l-map>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow h-80 cardP">
          <div class="card-header">
            <h5 class="m-0 pt-1 font-weight-bold float-left">Pegawai</h5>
          </div>
          <div class="card-body">
            <div class="scrollable">
              <div class="chat-list-wrapper">
                <!-- end chat-list -->
                <p v-if="isLoading">Loading ...</p>
                <a
                  v-else
                  href="javascript:;"
                  class="chat-list d-block"
                  v-for="user in usermaps"
                  :key="user.id"
                  @click="user.active = !user.active"
                >
                  <div class="chat-list-item">
                    <div class="image">
                      <img :src="user.user.foto" alt="" />
                      <span class="status"></span>
                    </div>
                    <div class="content">
                      <div class="title">
                        <h6 class="text-sm text-medium">
                          {{ user.user.nama }}
                        </h6>
                        <div class="d-flex align-items-center">
                          <!-- <span>2d</span> -->
                        </div>
                      </div>
                      <p class="text-sm">
                        {{ user.updated_at }}
                      </p>
                    </div>
                  </div>
                </a>
                <p v-if="usermaps.length == 0">Tidak Ada data</p>
                <!-- end chat-list -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
</style>

<script>
import {
  LMap,
  LTileLayer,
  LMarker,
  LIcon,
  LFeatureGroup,
  LPopup,
} from "vue2-leaflet";
import { latLng, icon } from "leaflet";
export default {
  name: "Maps",
  components: {
    LMap,
    LTileLayer,
    LMarker,
    LIcon,
    LFeatureGroup,
    LPopup,
  },

  computed: {
    // a computed getter
    getMarker: function () {
      var marker = [];
      this.users.map(function (key, value) {
        marker.push(value);
      });
      return marker;
    },
    dynamicSize() {
      return [this.iconSize, this.iconSize * 1.15];
    },
    dynamicAnchor() {
      return [this.iconSize / 2, this.iconSize * 1.15];
    },
  },
  data() {
    return {
      users: [],
      isLoading: true,
      icon: icon({
        iconUrl: "static/images/baseball-marker.png",
        iconSize: [32, 37],
        iconAnchor: [16, 37],
      }),
      url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
      attribution:
        '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      zoom: 12,
      center: [-6.2911, 106.715421],
      markerLatLng: [-6.3228784, 106.7077496],
      usermaps: {},
      map: null,
    };
  },
  methods: {
    getUser() {
      axios.get("/users/maps/get-loct").then(({ data }) => {
        data.forEach(function (element) {
          element.active = false;
        });
        this.usermaps = data[0];
        this.isLoading = false;
      });
    },
    loadUserMaps() {
      axios.get("/users/getUser").then(({ data }) => {
        this.users = data;
      });
    },
    reload() {
      this.isLoading = true;
      this.loadUserMaps();
      this.getUser();
    },
    doSomethingOnReady() {
      this.map = this.$refs.map.mapObject;
    },
    openDefaultMarkers(mapObject, nextMarker) {
      mapObject.openPopup();
    },
  },
  created() {
    axios.get("/users/me").then(({ data }) => {
      if (data.role_id != 1) {
        this.$router.push({ name: "not-found" });
      } else {
        this.loadUserMaps();
        this.getUser();
      }
    });

    // if (this.checkRole()) {
    //   this.loadUserMaps();
    //   this.getUser();
    // } else {
    //   this.$router.push({ name: "not-found" });
    // }
  },
  mounted() {
    // DO
    this.$nextTick(() => {
      //   console.log(this.$refs.map.mapObject);
    });
  },
};
</script>
<style scoped>
.cardP {
}
.scrollable {
  overflow-y: auto;
  max-height: 500px;
}
</style>
