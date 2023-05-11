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
            <button
              :disabled="isLoading"
              class="btn btn-primary btn-sm mb-2"
              @click="showAll()"
            >
              Show All
            </button>
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
                  v-for="marker in filteredList"
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
                        <small>{{ marker.user.unitkerjas }}</small>
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
            <input
              type="text"
              name="cari"
              placeholder="Cari ...."
              v-model="cari"
              :disabled="isLoading"
              id=""
              class="form-control"
            />
            <div class="scrollable">
              <div class="chat-list-wrapper">
                <!-- end chat-list -->
                <p v-if="isLoading" class="text-center">Loading ...</p>
                <a
                  v-else
                  href="javascript:;"
                  class="chat-list d-block"
                  v-for="user in filteredList"
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
                <!-- <p v-if="usermaps.length == 0">Tidak Ada data</p> -->
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
    filteredList() {
      let tempMaps = this.usermaps;
      if (this.cari != "" && this.cari) {
        tempMaps = this.usermaps.filter((usersmaps) => {
          return usersmaps.user.nama
            .toLowerCase()
            .includes(this.cari.toLowerCase());
        });
      }
      return tempMaps;
    },

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
      cari: "",
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
      usermaps: [],
      map: null,
    };
  },
  methods: {
    getUser() {
      axios.get("/users/maps/get-loct").then(({ data }) => {
        data.forEach(function (element) {
          //   console.log(element);
          element.active = false;
        });

        this.usermaps = data;
        // console.log(data);
        // console.log(data);
        this.isLoading = false;
      });
    },

    reload() {
      this.isLoading = true;

      this.getUser();
    },
    doSomethingOnReady() {
      this.map = this.$refs.map.mapObject;
    },
    openDefaultMarkers(mapObject, nextMarker) {
      mapObject.openPopup();
    },
    showAll() {
      this.usermaps.forEach(function (element) {
        //   console.log(element);
        element.active = !element.active;
      });

      //   this.usermaps = data;
    },
  },
  created() {
    document.querySelector("title").textContent = "Lokasi Pegawai";
    axios.get("/users/me").then(({ data }) => {
      let arr = [1, 2, 4, 5, 6];

      if (!arr.includes(data.role_id)) {
        // this.$router.push({ name: "not-found" });
      } else {
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
