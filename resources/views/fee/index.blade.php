<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <h2 class="text-2xl font-semibold mb-4 text-gray-700">💰 Fee Management</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Member Info -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">Member Information</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600">Name</label>
                        <input id="memberName" type="text" class="w-full bg-gray-200 rounded p-2" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Phone</label>
                        <input id="memberPhone" type="text" class="w-full bg-gray-200 rounded p-2" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Plan</label>
                        <input id="memberPlan" type="text" class="w-full bg-gray-200 rounded p-2" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Last Fee Date</label>
                        <input id="lastFee" type="text" class="w-full bg-gray-200 rounded p-2" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Next Fee Due</label>
                        <input id="nextFee" type="text" class="w-full bg-gray-200 rounded p-2" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Update Fee</label>
                        <input type="date" id="feeDate" class="w-full bg-gray-200 rounded p-2" readonly>
                    </div>
                                                            <div class="col-span-2">
                        <label class="block text-sm text-gray-600">Status</label>
                        <input id="status" type="text" class="w-full rounded p-2 font-semibold text-center" readonly>
                    </div>
                    <div class="col-span-2 flex justify-end mt-2">
                        <button id="updateFeeBtn" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Update & Print
                        </button>
                    </div>

                </div>
            </div>

            <!-- QR Scanner -->
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

                <audio id="successSound" src="{{ asset('sounds/success.mp3') }}"></audio>

                <div class="mt-4 w-full">
                    <label class="block text-sm text-gray-600">Or Search by Phone & ID:</label>
                    <input type="text" id="searchPhone" class="w-full border-gray-300 rounded-md p-2">
                    <button id="searchPhoneBtn"
                            class="mt-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 w-full">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        const startBtn = document.getElementById('startScan');
        const stopBtn = document.getElementById('stopScan');
        const html5QrCode = new Html5Qrcode("reader");
    
        const successSound = document.getElementById('successSound');
        const feeDateInput = document.getElementById('feeDate');
          // Set today date (YYYY-MM-DD)
        const today = new Date().toISOString().split('T')[0];
        feeDateInput.value = today;
        feeDateInput.setAttribute('readonly', true);

        let isScanning = false;
        let currentMemberId = null;

        // ✅ Start scanning
        startBtn.addEventListener('click', () => {
            if (!isScanning) {
                Html5Qrcode.getCameras().then(cameras => {
                    const cameraId = cameras.length ? cameras[0].id : null;
                    html5QrCode.start(
                        cameraId,
                        { fps: 10, qrbox: 200 },
                        qrCodeMessage => handleScan(qrCodeMessage),
                        errorMessage => {}
                    );
                    isScanning = true;
                    startBtn.classList.add('hidden');
                    stopBtn.classList.remove('hidden');
                });
            }
        });

        // ✅ Stop scanning
        stopBtn.addEventListener('click', () => {
            html5QrCode.stop();
            isScanning = false;
            startBtn.classList.remove('hidden');
            stopBtn.classList.add('hidden');
        });

        // ✅ Handle scanned QR code
        function handleScan(code) {
            successSound.play();
            searchMember({ member_code: code });
        }

        // ✅ Manual search by phone
        document.getElementById('searchPhoneBtn').addEventListener('click', () => {
            const phone = document.getElementById('searchPhone').value;
            if (phone) searchMember({ phone });
        });

        // ✅ Search member API
        function searchMember(data) {
            fetch("{{ route('fee.search') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    const m = res.data;
                    currentMemberId = m.id;
                    document.getElementById('searchPhone').value = '';
                    document.getElementById('memberName').value = m.name ?? '-';
                    document.getElementById('memberPhone').value = m.phone ?? '-';
                    document.getElementById('memberPlan').value = m.plan ?? '-';
                    document.getElementById('lastFee').value = m.last_fee_date ?? '-';
                    document.getElementById('nextFee').value = m.next_fee_due
                        ? new Date(m.next_fee_due).toISOString().split('T')[0]
                        : '-';
                    document.getElementById('feeDate').value = m.next_fee_due
                    ? new Date(m.next_fee_due).toISOString().split('T')[0]
                    : '';
                } else {
                    alert(res.message);
                }
            });
        }

        // ✅ Update fee API
        // document.getElementById('updateFeeBtn').addEventListener('click', () => {
        //     const feeDate = document.getElementById('feeDate').value;
        //     if (!currentMemberId || !feeDate) return alert('Select member and date first.');

        //     fetch("{{ route('fee.update') }}", {
        //         method: "POST",
        //         headers: {
        //             "Content-Type": "application/json",
        //             "X-CSRF-TOKEN": "{{ csrf_token() }}"
        //         },
        //         body: JSON.stringify({ member_id: currentMemberId, fee_date: feeDate })
        //     })
        //     .then(res => res.json())
        //     .then(res => {
        //         if (res.status === 'success') {
        //             const d = res.data;
        //             document.getElementById('lastFee').value = d.last_fee_date ?? '-';
        //             document.getElementById('nextFee').value = d.next_fee_due
        //                 ? new Date(d.next_fee_due).toISOString().split('T')[0]
        //                 : '-';
        //             successSound.play();
        //             alert(res.message);
        //         } else {
        //             alert(res.message);
        //         }
        //     });
        // });


        document.getElementById('updateFeeBtn').addEventListener('click', () => {
    const feeDate = document.getElementById('nextFee').value;
    if (!currentMemberId || !feeDate) {
        return alert('Select member first.');
    }

    fetch("{{ route('fee.update') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            member_id: currentMemberId,
            fee_date: feeDate
        })
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {

            // Update UI
            document.getElementById('lastFee').value = res.data.last_fee_date;
            document.getElementById('nextFee').value =
                new Date(res.data.next_fee_due).toISOString().split('T')[0];


                    const statusInput = document.getElementById("status");
                    statusInput.className = "w-full rounded p-2 font-semibold text-center"; // reset
                    document.getElementById('searchPhone').value = '';
                    if (!res.status === 'success') {
                        statusInput.value = "❌ Something went wrong !";
                        statusInput.classList.add('bg-red-100', 'text-red-600');
                    } else {
                        statusInput.value = "✅ Fee submit successfully";
                        statusInput.classList.add('bg-green-100', 'text-green-600');
                    }


            successSound.play();

            // ✅ OPEN PRINT WINDOW
            window.open(res.print_url, '_blank', 'width=350,height=600');
        } else {
            alert(res.message);
        }
    });
});




    </script>
    <script>
  // Aaj ki date lo (format: YYYY-MM-DD)
  const today = new Date().toISOString().split("T")[0];

  // Input element lo
  const dateInput = document.getElementById("feeDate");

  // Max attribute set karo (future dates disable karne ke liye)
  dateInput.setAttribute("max", today);
</script>
</x-app-layout>
