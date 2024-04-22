const firebaseConfig = {
    apiKey: "AIzaSyBwtZd67IoY5f6hxuvpwPExKT7gBqW6-zo",
    authDomain: "monitoring-1391c.firebaseapp.com",
    databaseURL: "https://monitoring-1391c-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "monitoring-1391c",
    storageBucket: "monitoring-1391c.appspot.com",
    messagingSenderId: "774071982846",
    appId: "1:774071982846:web:9d17c8f7c6b550a43ce3af",
    measurementId: "G-749V7E4GPW"
  };

  firebase.initializeApp(firebaseConfig);

//   struktur file firebase
var db = firebase.database();
var motorRef = db.ref('motors/MTR001');
motorRef.set({
    trackings: {
      trackingID1: { latitude: "-6.175110", longitude: "106.865036" },
      trackingID2: { latitude: "-6.190107", longitude: "106.838047" }
    },
    batteries: {
      batteryID1: { level: "75" },
      batteryID2: { level: "72" }
    },
    locks: {
      lockID1: { status: "1" },
      lockID2: { status: "0" }
    }
  });

var motorRef = db.ref('motors/MTR002');
motorRef.set({
    trackings: {
      trackingID1: { latitude: "-6.2087634", longitude: "106.845599" },
      trackingID2: { latitude: "-6.21462", longitude: "106.84513" }
    },
    batteries: {
      batteryID1: { level: "50" },
      batteryID2: { level: "48" }
    },
    locks: {
      lockID1: { status: "0" },
      lockID2: { status: "0" }
    }
  });

var motorRef = db.ref('motors/MTR003');
motorRef.set({
    trackings: {
      trackingID1: { latitude: "-6.121435", longitude: "106.774124" },
      trackingID2: { latitude: "-6.123456", longitude: "106.789012" }
    },
    batteries: {
      batteryID1: { level: "65" },
      batteryID2: { level: "60" }
    },
    locks: {
      lockID1: { status: "0" },
      lockID2: { status: "1" }
    }
  });