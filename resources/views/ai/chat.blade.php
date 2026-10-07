<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Business AI Assistant</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f4f7fb;
            font-family: Arial, sans-serif;
        }

        .chat-wrapper {
            max-width: 1000px;
            margin: 40px auto;
        }

        .chat-card {
            height: 700px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
        }

        .chat-header {
            background: #212529;
            color: white;
            padding: 20px 25px;
        }

        .chat-header h4 {
            margin: 0;
            font-weight: 700;
        }

        .chat-header p {
            margin: 5px 0 0;
            color: #ced4da;
        }

        .chat-body {
            height: 540px;
            overflow-y: auto;
            padding: 25px;
            background: #f8f9fa;
        }

        .message {
            display: flex;
            margin-bottom: 20px;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message.ai {
            justify-content: flex-start;
        }

        .message-content {
            max-width: 75%;
            padding: 14px 18px;
            border-radius: 16px;
            line-height: 1.6;
        }

        .user .message-content {
            background: #0d6efd;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .ai .message-content {
            background: white;
            color: #212529;
            border: 1px solid #e9ecef;
            border-bottom-left-radius: 4px;
        }

        .chat-footer {
            padding: 18px;
            background: white;
            border-top: 1px solid #dee2e6;
        }

        .question-input {
            border-radius: 12px;
            padding: 13px 16px;
        }

        .send-btn {
            border-radius: 12px;
            padding: 12px 20px;
        }

        .suggestion {
            cursor: pointer;
            transition: 0.2s;
        }

        .suggestion:hover {
            transform: translateY(-2px);
        }

        .typing {
            display: none;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="chat-wrapper">

            <div class="card chat-card">

                {{-- Header --}}
                <div class="chat-header">

                    <div class="d-flex align-items-center gap-3">

                        <div class="bg-primary rounded-circle p-3">
                            <i class="bi bi-robot fs-4"></i>
                        </div>

                        <div>
                            <h4>Business AI Assistant</h4>

                            <p>
                                Ask questions about your business data
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Chat Body --}}
                <div class="chat-body" id="chatBody">

                    {{-- Welcome Message --}}
                    <div class="message ai">

                        <div class="message-content">

                            <strong>Business AI</strong>

                            <div class="mt-2">
                                Hello! 👋
                                <br>
                                I can answer questions about your customers,
                                receivables and payables using your business data.
                            </div>

                        </div>

                    </div>


                    {{-- Suggestions --}}
                    <div class="mb-4">

                        <small class="text-muted d-block mb-2">
                            Try asking:
                        </small>

                        <div class="d-flex flex-wrap gap-2">

                            <button class="btn btn-outline-primary btn-sm suggestion"
                                onclick="askSuggestion('What are our total receivables?')">
                                Total Receivables
                            </button>

                            <button class="btn btn-outline-primary btn-sm suggestion"
                                onclick="askSuggestion('What are our total payables?')">
                                Total Payables
                            </button>

                            <button class="btn btn-outline-primary btn-sm suggestion"
                                onclick="askSuggestion('Which customers owe us money?')">
                                Customers Owing Money
                            </button>

                            <button class="btn btn-outline-primary btn-sm suggestion"
                                onclick="askSuggestion('What are our overdue receivables?')">
                                Overdue Receivables
                            </button>

                        </div>

                    </div>


                    {{-- Typing --}}
                    <div class="message ai typing" id="typingMessage">

                        <div class="message-content">

                            <i class="bi bi-three-dots"></i>

                            AI is thinking...

                        </div>

                    </div>

                </div>


                {{-- Footer --}}
                <div class="chat-footer">

                    <form id="chatForm">

                        <div class="input-group">

                            <input type="text" id="question" class="form-control question-input"
                                placeholder="Ask a business question..." autocomplete="off">

                            <button type="submit" class="btn btn-primary send-btn" id="sendButton">
                                <i class="bi bi-send"></i>
                                Send
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <script>
        const chatForm = document.getElementById('chatForm');

        const questionInput = document.getElementById('question');

        const chatBody = document.getElementById('chatBody');

        const typingMessage = document.getElementById('typingMessage');

        const sendButton = document.getElementById('sendButton');


        function askSuggestion(question) {

            questionInput.value = question;

            chatForm.dispatchEvent(new Event('submit'));

        }


        function addMessage(message, type) {

            const messageDiv = document.createElement('div');

            messageDiv.className = `message ${type}`;

            const contentDiv = document.createElement('div');

            contentDiv.className = 'message-content';

            if (type === 'ai') {

                contentDiv.innerHTML = `
                <strong>Business AI</strong>
                <div class="mt-2">
                    ${message}
                </div>
            `;

            } else {

                contentDiv.textContent = message;

            }

            messageDiv.appendChild(contentDiv);

            chatBody.appendChild(messageDiv);

            chatBody.scrollTop = chatBody.scrollHeight;
        }


        chatForm.addEventListener('submit', async function(event) {

            event.preventDefault();

            const question = questionInput.value.trim();

            if (!question) {
                return;
            }


            // Show user message
            addMessage(question, 'user');


            // Clear input
            questionInput.value = '';


            // Show typing
            typingMessage.style.display = 'flex';

            chatBody.scrollTop = chatBody.scrollHeight;


            // Disable button
            sendButton.disabled = true;


            try {

                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content');


                const response = await fetch('/ai/chat', {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'Accept': 'application/json',

                        'X-CSRF-TOKEN': csrfToken

                    },

                    body: JSON.stringify({

                        question: question

                    })

                });


                const data = await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message || 'Something went wrong.'
                    );

                }


                if (data.success) {

                    addMessage(
                        data.answer,
                        'ai'
                    );

                } else {

                    addMessage(
                        'Sorry, I could not process your question.',
                        'ai'
                    );

                }


            } catch (error) {

                console.error(error);

                addMessage(
                    'Error: ' + error.message,
                    'ai'
                );

            } finally {

                typingMessage.style.display = 'none';

                sendButton.disabled = false;

                questionInput.focus();

            }

        });
    </script>

</body>

</html>
