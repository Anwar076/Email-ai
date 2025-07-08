<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('AI Email Assistant Dashboard') }}
            </h2>
            <div class="flex space-x-4">
                <span id="connection-status" class="px-3 py-1 rounded-full text-sm font-medium">
                    Checking connection...
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Welcome to AI Email Assistant</h1>
                            <p class="text-blue-100 text-lg">Manage your Gmail inbox with AI-powered reply generation</p>
                        </div>
                        <div class="hidden md:block">
                            <svg class="w-24 h-24 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Gmail Connection Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Gmail Connection</h3>
                            <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        
                        <div id="gmail-connection-content">
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                                <span class="text-gray-600">Checking status...</span>
                            </div>
                            
                            <div id="connect-section" class="hidden">
                                <p class="text-gray-600 mb-4">Connect your Gmail account to start managing emails with AI assistance.</p>
                                <a href="{{ route('google.auth') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Connect Gmail
                                </a>
                            </div>
                            
                            <div id="connected-section" class="hidden">
                                <div class="flex items-center space-x-2 mb-4">
                                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                    <span class="text-green-600 font-medium">Gmail Connected</span>
                                </div>
                                <div class="space-y-2">
                                    <a href="{{ route('inbox') }}" class="block w-full text-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                                        View Inbox
                                    </a>
                                    <a href="{{ route('gmail.disconnect') }}" class="block w-full text-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-md transition duration-150 ease-in-out">
                                        Disconnect
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">AI Features</h3>
                            <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        
                        <ul class="space-y-3">
                            <li class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">AI-powered email replies</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Professional email tone</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">One-click sending</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Email summaries</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Quick Stats</h3>
                            <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Gmail Status</span>
                                <span id="status-badge" class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-700">Checking...</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">AI Model</span>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-200 text-green-700">GPT-3.5</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Security</span>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-200 text-green-700">OAuth2</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- How to Use Section -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Use AI Email Assistant</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-blue-600 font-bold text-lg">1</span>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-2">Connect Gmail</h4>
                            <p class="text-gray-600 text-sm">Securely connect your Gmail account using OAuth2</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-blue-600 font-bold text-lg">2</span>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-2">View Inbox</h4>
                            <p class="text-gray-600 text-sm">Browse your latest emails in a clean interface</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-blue-600 font-bold text-lg">3</span>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-2">Generate Reply</h4>
                            <p class="text-gray-600 text-sm">Let AI create professional responses to your emails</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-blue-600 font-bold text-lg">4</span>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-2">Send</h4>
                            <p class="text-gray-600 text-sm">Review and send AI-generated replies with one click</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for dynamic connection status -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            checkGmailConnection();
        });

        function checkGmailConnection() {
            fetch('{{ route("gmail.status") }}')
                .then(response => response.json())
                .then(data => {
                    const statusElement = document.getElementById('connection-status');
                    const statusBadge = document.getElementById('status-badge');
                    const connectSection = document.getElementById('connect-section');
                    const connectedSection = document.getElementById('connected-section');

                    if (data.connected) {
                        statusElement.className = 'px-3 py-1 rounded-full text-sm font-medium bg-green-200 text-green-700';
                        statusElement.textContent = 'Gmail Connected';
                        statusBadge.className = 'px-2 py-1 text-xs rounded-full bg-green-200 text-green-700';
                        statusBadge.textContent = 'Connected';
                        connectSection.classList.add('hidden');
                        connectedSection.classList.remove('hidden');
                    } else {
                        statusElement.className = 'px-3 py-1 rounded-full text-sm font-medium bg-red-200 text-red-700';
                        statusElement.textContent = 'Gmail Not Connected';
                        statusBadge.className = 'px-2 py-1 text-xs rounded-full bg-red-200 text-red-700';
                        statusBadge.textContent = 'Disconnected';
                        connectSection.classList.remove('hidden');
                        connectedSection.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error checking connection:', error);
                    const statusElement = document.getElementById('connection-status');
                    statusElement.className = 'px-3 py-1 rounded-full text-sm font-medium bg-yellow-200 text-yellow-700';
                    statusElement.textContent = 'Connection Error';
                });
        }
    </script>
</x-app-layout>