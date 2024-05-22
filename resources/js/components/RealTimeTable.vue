<template>
    <div>
        <table>
            <thead>
                <tr>
                    <th>Motor ID</th>
                    <th>Date</th>
                    <th>Battery Percentage</th>
                    <th>Battery Kilometers</th>
                    <th>Location</th>
                    <th>Lock Status</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="motor in motors" :key="motor.motors_id">
                    <tr v-for="(tracking, index) in motor.trackings" :key="index">
                        <td>{{ motor.motors_id }}</td>
                        <td>{{ tracking ? tracking.created_at : 'Data tidak ditemukan' }}</td>
                        <td>{{ motor.batteries[index] ? motor.batteries[index].percentage + '%' : 'Data tidak ditemukan' }}</td>
                        <td>{{ motor.batteries[index] ? motor.batteries[index].kilometers + ' km' : 'Data tidak ditemukan' }}</td>
                        <td>{{ tracking ? tracking.location_name : 'Lokasi tidak ditemukan' }}</td>
                        <td>{{ motor.locks[index] ? (motor.locks[index].status ? 'On' : 'Off') : 'Off' }}</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import Echo from 'laravel-echo';

export default {
    setup() {
        const motors = ref([]);

        const fetchData = async () => {
            try {
                const response = await fetch('/api/motors');
                const data = await response.json();
                motors.value = data;
            } catch (error) {
                console.error('Error fetching motors:', error);
            }
        };

        onMounted(() => {
            fetchData();

            window.Echo = new Echo({
                broadcaster: 'reverb',
                host: window.location.hostname + ':8080',
            });

            window.Echo.channel('motors')
                .listen('MotorUpdated', (e) => {
                    const index = motors.value.findIndex(m => m.motors_id === e.motor.motors_id);
                    if (index !== -1) {
                        motors.value[index] = e.motor;
                    } else {
                        motors.value.push(e.motor);
                    }
                });
        });

        return {
            motors,
        };
    },
};
</script>
