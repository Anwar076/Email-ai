<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gmail Inbox') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md transition duration-150 ease-in-out">
                    Back to Dashboard
                </a>
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

            <!-- Inbox Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Your Gmail Inbox</h3>
                                <p class="text-gray-600">Latest {{ count($emails) }} emails with AI assistant features</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 bg-green-200 text-green-700 rounded-full text-sm font-medium">Gmail Connected</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email List -->
            @if(count($emails) > 0)
                <div class="space-y-4">
                    @foreach($emails as $email)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg email-item" data-email-id="{{ $email['id'] }}">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">
                                                    {{ strtoupper(substr(trim(explode('<', $email['from'])[0]), 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                                    {{ trim(explode('<', $email['from'])[0]) ?: $email['from'] }}
                                                </h4>
                                                <p class="text-xs text-gray-500">{{ $email['date'] }}</p>
                                            </div>
                                        </div>
                                        
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $email['subject'] ?: '(No Subject)' }}</h3>
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $email['snippet'] }}</p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-3 mt-4">
                                    <button onclick="generateReply('{{ $email['id'] }}')" 
                                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-md transition duration-150 ease-in-out text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                        </svg>
                                        Generate AI Reply
                                    </button>
                                    
                                    <button onclick="viewEmail('{{ $email['id'] }}')" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        View Email
                                    </button>
                                    
                                    <button onclick="generateSummary('{{ $email['id'] }}')" 
                                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-150 ease-in-out text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        AI Summary
                                    </button>
                                </div>

                                <!-- AI Reply Section (Initially Hidden) -->
                                <div id="reply-section-{{ $email['id'] }}" class="hidden mt-6 p-4 bg-gray-50 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-3">AI Generated Reply</h4>
                                    <div id="reply-content-{{ $email['id'] }}" class="bg-white p-4 border rounded-lg mb-4"></div>
                                    <div class="flex space-x-3">
                                        <button onclick="sendReply('{{ $email['id'] }}')" 
                                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-150 ease-in-out text-sm">
                                            Send AI Reply
                                        </button>
                                        <button onclick="hideReply('{{ $email['id'] }}')" 
                                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-md transition duration-150 ease-in-out text-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary Section (Initially Hidden) -->
                                <div id="summary-section-{{ $email['id'] }}" class="hidden mt-6 p-4 bg-blue-50 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-3">AI Summary</h4>
                                    <div id="summary-content-{{ $email['id'] }}" class="text-gray-700"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No emails found</h3>
                        <p class="text-gray-600">Your inbox appears to be empty or there was an issue loading emails.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loading-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600 mx-auto mb-4"></div>
                <h3 class="text-lg font-medium text-gray-900 mb-2" id="loading-text">Processing...</h3>
                <p class="text-gray-600 text-sm" id="loading-subtitle">Please wait while AI processes your request</p>
            </div>
        </div>
    </div>

    <!-- Email Details Modal -->
    <div id="email-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Email Details</h3>
                <button onclick="closeEmailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div id="email-details-content">
                <!-- Email details will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function showLoading(text = 'Processing...', subtitle = 'Please wait while AI processes your request') {
            document.getElementById('loading-text').textContent = text;
            document.getElementById('loading-subtitle').textContent = subtitle;
            document.getElementById('loading-modal').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loading-modal').classList.add('hidden');
        }

        function generateReply(messageId) {
            showLoading('Generating AI Reply...', 'Our AI is crafting a professional response');
            
            fetch(`/generate-reply/${messageId}`)
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        document.getElementById(`reply-content-${messageId}`).innerHTML = data.reply;
                        document.getElementById(`reply-section-${messageId}`).classList.remove('hidden');
                        
                        // Scroll to the reply section
                        document.getElementById(`reply-section-${messageId}`).scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                    } else {
                        alert('Error generating reply: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    alert('Error generating reply. Please try again.');
                });
        }

        function sendReply(messageId) {
            if (!confirm('Are you sure you want to send this AI-generated reply?')) {
                return;
            }

            showLoading('Sending Reply...', 'Your AI-generated reply is being sent');
            
            fetch(`/send-reply/${messageId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    alert('Reply sent successfully!');
                    document.getElementById(`reply-section-${messageId}`).classList.add('hidden');
                } else {
                    alert('Error sending reply: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                alert('Error sending reply. Please try again.');
            });
        }

        function hideReply(messageId) {
            document.getElementById(`reply-section-${messageId}`).classList.add('hidden');
        }

        function generateSummary(messageId) {
            showLoading('Generating Summary...', 'AI is analyzing the email content');
            
            fetch(`/email/${messageId}/summary`)
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        document.getElementById(`summary-content-${messageId}`).textContent = data.summary;
                        document.getElementById(`summary-section-${messageId}`).classList.remove('hidden');
                        
                        // Scroll to the summary section
                        document.getElementById(`summary-section-${messageId}`).scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                    } else {
                        alert('Error generating summary: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    alert('Error generating summary. Please try again.');
                });
        }

        function viewEmail(messageId) {
            showLoading('Loading Email...', 'Fetching email details');
            
            fetch(`/email/${messageId}`)
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        const email = data.email;
                        document.getElementById('email-details-content').innerHTML = `
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">From</label>
                                    <p class="mt-1 text-sm text-gray-900">${email.from}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">To</label>
                                    <p class="mt-1 text-sm text-gray-900">${email.to || 'N/A'}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Subject</label>
                                    <p class="mt-1 text-sm text-gray-900">${email.subject || '(No Subject)'}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date</label>
                                    <p class="mt-1 text-sm text-gray-900">${email.date}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Content</label>
                                    <div class="mt-1 p-4 border rounded-lg bg-gray-50 max-h-96 overflow-y-auto">
                                        ${email.body || email.snippet}
                                    </div>
                                </div>
                            </div>
                        `;
                        document.getElementById('email-modal').classList.remove('hidden');
                    } else {
                        alert('Error loading email: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    alert('Error loading email. Please try again.');
                });
        }

        function closeEmailModal() {
            document.getElementById('email-modal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const loadingModal = document.getElementById('loading-modal');
            const emailModal = document.getElementById('email-modal');
            
            if (event.target === loadingModal) {
                hideLoading();
            }
            
            if (event.target === emailModal) {
                closeEmailModal();
            }
        });
    </script>
</x-app-layout>