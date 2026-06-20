<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Interview AI</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">

<div class="max-w-7xl mx-auto p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-4xl font-bold text-blue-600">
                Visa Interview AI
            </h1>

            <p class="text-gray-500">
                Practice. Prepare. Get Approved.
            </p>
        </div>

        <div class="flex gap-4">
            <select class="border rounded-lg px-4 py-2">
                <option>English</option>
            </select>

            <button class="border border-red-500 text-red-500 px-6 py-2 rounded-lg">
                End Interview
            </button>
        </div>
    </div>

    <!-- Top Cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">

        <div class="bg-white rounded-xl p-5 shadow">
            <p class="text-sm text-gray-500">VISA TYPE</p>
            <h3 class="font-semibold text-lg">
                F1 Student Visa
            </h3>
        </div>

        <div class="bg-white rounded-xl p-5 shadow">
            <p class="text-sm text-gray-500">INTERVIEW MODE</p>
            <h3 class="font-semibold text-lg">
                US Embassy Interview
            </h3>
        </div>

        <div class="bg-white rounded-xl p-5 shadow">
            <p class="text-sm text-gray-500">ELAPSED TIME</p>
            <h3 class="font-semibold text-lg">
                00:00
            </h3>
        </div>

    </div>

    <div class="grid grid-cols-12 gap-6">

        <!-- Chat Section -->
        <div class="col-span-8 bg-white rounded-xl shadow p-6">

            <div class="space-y-6">

                <!-- AI Question -->
                <div>
                    <p class="text-blue-600 font-semibold">
                        AI CONSULAR OFFICER
                    </p>

                    <p class="mt-2">
                        Why do you want to study in the United States?
                    </p>
                </div>

                <!-- User Answer -->
                <div class="flex justify-end">
                    <div class="bg-blue-50 p-4 rounded-xl max-w-lg">
                        I want to study Computer Science because the program aligns with my career goals.
                    </div>
                </div>

            </div>

            <!-- Answer Box -->
            <div class="mt-10 flex gap-2">

                <input
                    type="text"
                    placeholder="Type your answer here..."
                    class="flex-1 border rounded-xl px-4 py-3"
                >

                <button class="bg-blue-600 text-white px-6 rounded-xl">
                    Send
                </button>

            </div>

        </div>

        <!-- Sidebar -->
        <div class="col-span-4 space-y-4">

            <!-- Progress -->
            <div class="bg-white rounded-xl p-5 shadow">

                <h3 class="font-bold text-blue-600 mb-3">
                    INTERVIEW PROGRESS
                </h3>

                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full w-2/5"></div>
                </div>

                <p class="mt-2 text-gray-600">
                    40% Completed
                </p>

            </div>

            <!-- Feedback -->
            <div class="bg-white rounded-xl p-5 shadow">

                <h3 class="font-bold text-blue-600 mb-4">
                    AI FEEDBACK
                </h3>

                <ul class="space-y-3">

                    <li>
                        ✅ Clarity: Good
                    </li>

                    <li>
                        ✅ Confidence: Strong
                    </li>

                    <li>
                        ⚠️ Add more details about your career goals.
                    </li>

                </ul>

            </div>

            <!-- Tips -->
            <div class="bg-white rounded-xl p-5 shadow">

                <h3 class="font-bold text-blue-600 mb-4">
                    COMMON TIPS
                </h3>

                <ul class="space-y-3 text-sm">

                    <li>✔ Be Honest</li>

                    <li>✔ Be Clear</li>

                    <li>✔ Show Strong Home Ties</li>

                    <li>✔ Know Your Study Plan</li>

                </ul>

            </div>

        </div>

    </div>

</div>

</body>
</html>