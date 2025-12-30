<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <h2 class="text-2xl font-semibold mb-4 text-gray-700">📷 Attendance Scanner</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- ✅ Member Details -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">Member Information</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600">Name</label>
                        <input id="memberName" type="text" class="w-full bg-gray-200 rounded p-2" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Membership</label>
                        <input id="membership" type="text" class="w-full bg-gray-200 rounded p-2" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Starts At</label>
                        <input id="startDate" type="text" class="w-full bg-gray-200 rounded p-2" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Ends At</label>
                        <input id="endDate" type="text" class="w-full bg-gray-200 rounded p-2" readonly>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm text-gray-600">Status</label>
                        <input id="status" type="text" class="w-full rounded p-2 font-semibold text-center" readonly>
                    </div>
                </div>
            </div>

            <!-- ✅ QR Scanner + Search by Phone -->
            <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
                <div id="reader" style="width: 300px;"></div>
                <button id="startScan" 
                        class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Start Scanning
                </button>
                <button id="stopScan" 
                        class="mt-2 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 hidden">
                    Stop Scanning
                </button>

                <!-- ✅ Success Sound -->
                <audio id="successSound" src="{{ asset('sounds/success.mp3') }}"></audio>

                <!-- ✅ Manual Search by Phone -->
                <div class="mt-4 w-full">
                    <label class="block text-sm text-gray-600">Search by Phone or ID:</label>
                    <input type="text" id="searchPhone" class="w-full border-gray-300 rounded-md p-2">
                    <button id="searchPhoneBtn"
                            class="mt-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 w-full">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Include HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        const startBtn = document.getElementById('startScan');
        const stopBtn = document.getElementById('stopScan');
        const html5QrCode = new Html5Qrcode("reader");
        const successSound = document.getElementById('successSound');
        let isScanning = false;

        // ✅ Start Scanning
        startBtn.addEventListener('click', () => {
            if (!isScanning) {
                Html5Qrcode.getCameras().then(cameras => {
                    const cameraId = cameras.length ? cameras[0].id : null;
                    html5QrCode.start(
                        cameraId,
                        { fps: 10, qrbox: 200 },
                        qrCodeMessage => handleSearch({ member_code: qrCodeMessage }),
                        errorMessage => {}
                    );
                    isScanning = true;
                    startBtn.classList.add('hidden');
                    stopBtn.classList.remove('hidden');
                });
            }
        });

        // ✅ Stop Scanning
        stopBtn.addEventListener('click', () => {
            html5QrCode.stop();
            isScanning = false;
            startBtn.classList.remove('hidden');
            stopBtn.classList.add('hidden');
        });

        // ✅ Manual Search by Phone
        document.getElementById('searchPhoneBtn').addEventListener('click', () => {
            const phone = document.getElementById('searchPhone').value;
            if (phone) handleSearch({ phone });
        });

        // ✅ Common Search Function (for QR & phone both)
        function handleSearch(data) {
            fetch("{{ route('attendance.scan') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === "success") {
                    const member = res.data;
                    
                    // 🔊 Play success sound
                    successSound.play();

                    // 🧾 Fill info
                    document.getElementById("memberName").value = member.name;
                    document.getElementById("membership").value = member.plan;
                    document.getElementById("startDate").value = member.start;
                    document.getElementById("endDate").value = member.end;

                    const statusInput = document.getElementById("status");
                    statusInput.className = "w-full rounded p-2 font-semibold text-center"; // reset
                    document.getElementById('searchPhone').value = '';
                    if (member.is_expired) {
                        statusInput.value = "❌ Membership Expired! Please pay fee.";
                        statusInput.classList.add('bg-red-100', 'text-red-600');
                    } else {
                        statusInput.value = "✅ Attendance Marked - Active Member";
                        statusInput.classList.add('bg-green-100', 'text-green-600');
                    }
                } else {
                    alert(res.message);
                }
            });
        }
    </script>
</x-app-layout>
