<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        Business AI Assistant
    </title>


    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>
        body {

            background: #f4f7fb;

            font-family: Arial, sans-serif;

        }


        /* ============================================================
           MAIN WRAPPER
        ============================================================ */

        .chat-wrapper {

            max-width: 1250px;

            margin: 40px auto;

        }


        /* ============================================================
           CHAT CARD
        ============================================================ */

        .chat-card {

            height: 700px;

            border: none;

            border-radius: 20px;

            overflow: hidden;

            box-shadow:
                0 10px 35px rgba(0, 0, 0, 0.08);

        }


        /* ============================================================
           HEADER
        ============================================================ */

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


        /* ============================================================
           CHAT BODY
        ============================================================ */

        .chat-body {

            height: 540px;

            overflow-y: auto;

            padding: 25px;

            background: #f8f9fa;

        }


        /* ============================================================
           MESSAGE
        ============================================================ */

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

            word-wrap: break-word;

            overflow-wrap: break-word;

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


        .message-time {

            font-size: 11px;

            opacity: 0.65;

            margin-top: 5px;

        }


        /* ============================================================
           FOOTER
        ============================================================ */

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


        /* ============================================================
           SUGGESTIONS
        ============================================================ */

        .suggestion {

            cursor: pointer;

            transition: 0.2s;

        }


        .suggestion:hover {

            transform: translateY(-2px);

        }


        /* ============================================================
           TYPING
        ============================================================ */

        .typing {

            display: none;

        }


        /* ============================================================
           HISTORY SIDEBAR
        ============================================================ */

        .conversation-card {

            height: 700px;

            border: none;

            border-radius: 20px;

            overflow: hidden;

            box-shadow:
                0 10px 35px rgba(0, 0, 0, 0.08);

        }


        .conversation-header {

            padding: 18px;

            background: white;

            border-bottom: 1px solid #dee2e6;

        }


        .conversation-list {

            height: calc(700px - 125px);

            overflow-y: auto;

            background: #f8f9fa;

        }


        .conversation-item {

            padding: 12px 12px;

            border-bottom: 1px solid #e9ecef;

            cursor: pointer;

            transition: 0.2s;

            background: white;

        }


        .conversation-item:hover {

            background: #f1f5f9;

        }


        .conversation-item.active {

            background: #e7f1ff;

            border-left: 4px solid #0d6efd;

        }


        .conversation-content {

            min-width: 0;

            flex: 1;

        }


        .conversation-title {

            font-size: 14px;

            font-weight: 600;

            color: #212529;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

        }


        .conversation-meta {

            font-size: 11px;

            color: #6c757d;

            margin-top: 5px;

        }


        /* ============================================================
           DELETE BUTTON
        ============================================================ */

        .delete-conversation-btn {

            flex-shrink: 0;

            width: 34px;

            height: 34px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 8px;

            border: 1px solid #dee2e6;

            background: white;

            color: #dc3545;

            transition: 0.2s;

        }


        .delete-conversation-btn:hover {

            background: #dc3545;

            color: white;

            border-color: #dc3545;

        }


        .conversation-pagination {

            padding: 12px;

            background: white;

            border-top: 1px solid #dee2e6;

        }


        /* ============================================================
           LOAD OLDER
        ============================================================ */

        .history-loader {

            display: none;

            text-align: center;

            margin-bottom: 20px;

        }


        /* ============================================================
           MOBILE
        ============================================================ */

        @media (max-width: 991px) {

            .conversation-card {

                height: auto;

            }


            .conversation-list {

                height: 250px;

            }


            .chat-card {

                height: 700px;

            }

        }
    </style>

</head>


<body>


    <div class="container-fluid">

        <div class="chat-wrapper">

            <div class="row g-3">


                {{-- =====================================================
                SIDEBAR
            ====================================================== --}}

                <div class="col-lg-3">

                    <div class="card conversation-card">


                        {{-- Sidebar Header --}}
                        <div class="conversation-header">

                            <strong>

                                <i class="bi bi-clock-history"></i>

                                Chat History

                            </strong>


                            <button type="button" class="btn btn-primary btn-sm w-100 mt-3" id="sidebarNewChatButton">

                                <i class="bi bi-plus-lg"></i>

                                New Chat

                            </button>

                        </div>


                        {{-- Conversation List --}}
                        <div class="conversation-list" id="conversationList">

                            <div class="text-center text-muted py-4">

                                <div class="spinner-border spinner-border-sm"></div>

                                <div class="small mt-2">

                                    Loading chats...

                                </div>

                            </div>

                        </div>


                        {{-- Conversation Pagination --}}
                        <div class="conversation-pagination text-center" id="conversationPagination">

                            <button type="button" class="btn btn-outline-secondary btn-sm w-100"
                                id="loadMoreConversationsButton">

                                Load More

                            </button>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                CHAT
            ====================================================== --}}

                <div class="col-lg-9">

                    <div class="card chat-card">


                        {{-- Header --}}
                        <div class="chat-header">

                            <div class="d-flex justify-content-between align-items-center gap-3">

                                <div class="d-flex align-items-center gap-3">

                                    <div class="bg-primary rounded-circle p-3">

                                        <i class="bi bi-robot fs-4"></i>

                                    </div>


                                    <div>

                                        <h4>
                                            Business AI Assistant
                                        </h4>


                                        <p>
                                            Ask questions about your business data
                                        </p>

                                    </div>

                                </div>


                                <button type="button" class="btn btn-outline-light btn-sm" id="newChatButton">

                                    <i class="bi bi-plus-lg"></i>

                                    New Chat

                                </button>

                            </div>

                        </div>


                        {{-- Chat Body --}}
                        <div class="chat-body" id="chatBody">


                            {{-- Load Older Messages --}}
                            <div class="history-loader" id="historyLoader">

                                <button type="button" class="btn btn-sm btn-outline-secondary" id="loadOlderButton">

                                    <i class="bi bi-arrow-up"></i>

                                    Load Older Messages

                                </button>

                            </div>


                            {{-- Welcome --}}
                            <div class="message ai" id="welcomeMessage">

                                <div class="message-content">

                                    <strong>
                                        Business AI
                                    </strong>


                                    <div class="mt-2">

                                        Hello! 👋

                                        <br>

                                        I can answer questions about your
                                        customers, receivables and payables
                                        using your business data.

                                    </div>

                                </div>

                            </div>


                            {{-- Suggestions --}}
                            <div class="mb-4" id="suggestions">

                                <small class="text-muted d-block mb-2">

                                    Try asking:

                                </small>


                                <div class="d-flex flex-wrap gap-2">

                                    <button type="button" class="btn btn-outline-primary btn-sm suggestion"
                                        onclick="askSuggestion('What are our total receivables?')">

                                        Total Receivables

                                    </button>


                                    <button type="button" class="btn btn-outline-primary btn-sm suggestion"
                                        onclick="askSuggestion('What are our total payables?')">

                                        Total Payables

                                    </button>


                                    <button type="button" class="btn btn-outline-primary btn-sm suggestion"
                                        onclick="askSuggestion('Which customers owe us money?')">

                                        Customers Owing Money

                                    </button>


                                    <button type="button" class="btn btn-outline-primary btn-sm suggestion"
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

        </div>

    </div>


    <script>
        /* ============================================================
               ELEMENTS
            ============================================================ */

        const chatForm =
            document.getElementById('chatForm');


        const questionInput =
            document.getElementById('question');


        const chatBody =
            document.getElementById('chatBody');


        const typingMessage =
            document.getElementById('typingMessage');


        const sendButton =
            document.getElementById('sendButton');


        const welcomeMessage =
            document.getElementById('welcomeMessage');


        const suggestions =
            document.getElementById('suggestions');


        const historyLoader =
            document.getElementById('historyLoader');


        const loadOlderButton =
            document.getElementById('loadOlderButton');


        const newChatButton =
            document.getElementById('newChatButton');


        const sidebarNewChatButton =
            document.getElementById('sidebarNewChatButton');


        const conversationList =
            document.getElementById('conversationList');


        const conversationPagination =
            document.getElementById('conversationPagination');


        const loadMoreConversationsButton =
            document.getElementById(
                'loadMoreConversationsButton'
            );


        /* ============================================================
           CONVERSATION STATE
        ============================================================ */

        let conversationId =
            localStorage.getItem(
                'conversation_id'
            );


        let currentMessagePage = 1;

        let lastMessagePage = 1;

        let currentConversationPage = 1;

        let lastConversationPage = 1;

        let loadingMessages = false;

        let loadingConversations = false;


        /* ============================================================
           SUGGESTION
        ============================================================ */

        function askSuggestion(question) {

            questionInput.value = question;

            chatForm.dispatchEvent(
                new Event('submit')
            );

        }


        /* ============================================================
           ESCAPE HTML
        ============================================================ */

        function escapeHtml(text) {

            const div =
                document.createElement('div');

            div.textContent =
                text ?? '';

            return div.innerHTML;

        }


        /* ============================================================
           FORMAT MESSAGE
        ============================================================ */

        function formatMessage(message) {

            return escapeHtml(message)
                .replace(/\n/g, '<br>');

        }


        /* ============================================================
           FORMAT DATE
        ============================================================ */

        function formatDate(date) {

            if (!date) {

                return '';

            }


            const dateObject =
                new Date(date);


            if (
                isNaN(
                    dateObject.getTime()
                )
            ) {

                return '';

            }


            return dateObject.toLocaleString();

        }


        /* ============================================================
           HIDE WELCOME
        ============================================================ */

        function hideWelcome() {

            if (welcomeMessage) {

                welcomeMessage.style.display =
                    'none';

            }


            if (suggestions) {

                suggestions.style.display =
                    'none';

            }

        }


        /* ============================================================
           SHOW NEW CHAT SCREEN
        ============================================================ */

        function showNewChatScreen() {

            welcomeMessage.style.display =
                'flex';


            suggestions.style.display =
                'block';


            historyLoader.style.display =
                'none';

        }


        /* ============================================================
           ADD MESSAGE
        ============================================================ */

        function addMessage(
            message,
            type,
            createdAt = null
        ) {

            const messageDiv =
                document.createElement('div');


            messageDiv.className =
                `message ${type}`;


            const contentDiv =
                document.createElement('div');


            contentDiv.className =
                'message-content';


            if (type === 'ai') {

                contentDiv.innerHTML = `

                <strong>
                    Business AI
                </strong>

                <div class="mt-2">

                    ${formatMessage(message)}

                </div>

                ${
                    createdAt
                        ? `
                                    <div class="message-time">
                                        ${formatDate(createdAt)}
                                    </div>
                                  `
                        : ''
                }

            `;

            } else {

                contentDiv.innerHTML = `

                <div>

                    ${escapeHtml(message)}

                </div>

                ${
                    createdAt
                        ? `
                                    <div class="message-time">
                                        ${formatDate(createdAt)}
                                    </div>
                                  `
                        : ''
                }

            `;

            }


            messageDiv.appendChild(
                contentDiv
            );


            chatBody.appendChild(
                messageDiv
            );


            chatBody.scrollTop =
                chatBody.scrollHeight;

        }


        /* ============================================================
           ADD MESSAGE AT TOP
        ============================================================ */

        function addMessageAtTop(
            message,
            type,
            createdAt = null
        ) {

            const messageDiv =
                document.createElement('div');


            messageDiv.className =
                `message ${type}`;


            const contentDiv =
                document.createElement('div');


            contentDiv.className =
                'message-content';


            if (type === 'ai') {

                contentDiv.innerHTML = `

                <strong>
                    Business AI
                </strong>

                <div class="mt-2">

                    ${formatMessage(message)}

                </div>

                ${
                    createdAt
                        ? `
                                    <div class="message-time">
                                        ${formatDate(createdAt)}
                                    </div>
                                  `
                        : ''
                }

            `;

            } else {

                contentDiv.innerHTML = `

                <div>

                    ${escapeHtml(message)}

                </div>

                ${
                    createdAt
                        ? `
                                    <div class="message-time">
                                        ${formatDate(createdAt)}
                                    </div>
                                  `
                        : ''
                }

            `;

            }


            messageDiv.appendChild(
                contentDiv
            );


            /*
            |--------------------------------------------------------------------------
            | Insert before first real message
            |--------------------------------------------------------------------------
            */

            const firstMessage =
                chatBody.querySelector(
                    '.message:not(#welcomeMessage):not(#typingMessage)'
                );


            if (firstMessage) {

                chatBody.insertBefore(
                    messageDiv,
                    firstMessage
                );

            } else {

                chatBody.appendChild(
                    messageDiv
                );

            }

        }


        /* ============================================================
           LOAD CONVERSATIONS
        ============================================================ */

        async function loadConversations(
            page = 1,
            append = false
        ) {

            if (loadingConversations) {

                return;

            }


            loadingConversations = true;


            try {

                const response =
                    await fetch(
                        `/ai/conversations?page=${page}`, {
                            method: 'GET',

                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );


                const data =
                    await response.json();


                if (
                    !response.ok ||
                    !data.success
                ) {

                    throw new Error(
                        data.message ||
                        'Unable to load conversations.'
                    );

                }


                const pagination =
                    data.conversations;


                currentConversationPage =
                    pagination.current_page;


                lastConversationPage =
                    pagination.last_page;


                if (!append) {

                    conversationList.innerHTML =
                        '';

                }


                const conversations =
                    pagination.data;


                if (
                    conversations.length === 0 &&
                    !append
                ) {

                    conversationList.innerHTML = `

                    <div class="text-center text-muted py-5">

                        <i class="bi bi-chat-square-text fs-2"></i>

                        <div class="small mt-2">

                            No conversations yet.

                        </div>

                    </div>

                `;

                }


                conversations.forEach(
                    function(conversation) {

                        const item =
                            document.createElement('div');


                        item.className =
                            'conversation-item';


                        if (
                            conversationId &&
                            Number(conversationId) ===
                            Number(conversation.id)
                        ) {

                            item.classList.add(
                                'active'
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Conversation HTML
                        |--------------------------------------------------------------------------
                        */

                        item.innerHTML = `

                        <div class="d-flex align-items-center gap-2">

                            <div class="conversation-content">

                                <div class="conversation-title">

                                    ${escapeHtml(
                                        conversation.title ||
                                        'New Conversation'
                                    )}

                                </div>


                                <div class="conversation-meta">

                                    <i class="bi bi-chat"></i>

                                    ${conversation.messages_count}

                                    messages

                                </div>

                            </div>


                            <button
                                type="button"
                                class="delete-conversation-btn"
                                title="Delete conversation"
                            >

                                <i class="bi bi-trash"></i>

                            </button>

                        </div>

                    `;


                        /*
                        |--------------------------------------------------------------------------
                        | Open Conversation
                        |--------------------------------------------------------------------------
                        */

                        item.addEventListener(
                            'click',
                            function() {

                                openConversation(
                                    conversation.id
                                );

                            }
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Delete Conversation
                        |--------------------------------------------------------------------------
                        */

                        const deleteButton =
                            item.querySelector(
                                '.delete-conversation-btn'
                            );


                        deleteButton.addEventListener(
                            'click',
                            function(event) {

                                event.stopPropagation();


                                deleteConversation(
                                    conversation.id
                                );

                            }
                        );


                        conversationList.appendChild(
                            item
                        );

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Load More Button
                |--------------------------------------------------------------------------
                */

                if (
                    currentConversationPage <
                    lastConversationPage
                ) {

                    conversationPagination.style.display =
                        'block';

                    loadMoreConversationsButton.disabled =
                        false;

                } else {

                    conversationPagination.style.display =
                        'none';

                }

            } catch (error) {

                console.error(
                    'Conversation Error:',
                    error
                );


                if (!append) {

                    conversationList.innerHTML = `

                    <div class="text-center text-danger p-3">

                        Unable to load chat history.

                    </div>

                `;

                }

            } finally {

                loadingConversations = false;

            }

        }


        /* ============================================================
           LOAD MORE CONVERSATIONS
        ============================================================ */

        loadMoreConversationsButton.addEventListener(
            'click',
            async function() {

                if (
                    currentConversationPage >=
                    lastConversationPage
                ) {

                    return;

                }


                await loadConversations(
                    currentConversationPage + 1,
                    true
                );

            }
        );


        /* ============================================================
           DELETE CONVERSATION
        ============================================================ */

        async function deleteConversation(id) {

            const confirmed =
                confirm(
                    'Are you sure you want to delete this conversation?\n\nAll messages in this chat will also be deleted.'
                );


            if (!confirmed) {

                return;

            }


            try {

                const csrfToken =
                    document
                    .querySelector(
                        'meta[name="csrf-token"]'
                    )
                    .getAttribute('content');


                const response =
                    await fetch(
                        `/ai/chat/${id}`, {

                            method: 'DELETE',

                            headers: {

                                'Accept': 'application/json',

                                'X-CSRF-TOKEN': csrfToken

                            }

                        }
                    );


                const data =
                    await response.json();


                if (
                    !response.ok ||
                    !data.success
                ) {

                    throw new Error(
                        data.message ||
                        'Unable to delete conversation.'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | If Deleted Chat Is Currently Open
                |--------------------------------------------------------------------------
                */

                if (
                    conversationId &&
                    Number(conversationId) ===
                    Number(id)
                ) {

                    localStorage.removeItem(
                        'conversation_id'
                    );


                    conversationId =
                        null;


                    currentMessagePage =
                        1;


                    lastMessagePage =
                        1;


                    const messages =
                        chatBody.querySelectorAll(
                            '.message:not(#welcomeMessage):not(#typingMessage)'
                        );


                    messages.forEach(
                        function(message) {

                            message.remove();

                        }
                    );


                    showNewChatScreen();

                }


                /*
                |--------------------------------------------------------------------------
                | Reload Sidebar From Page 1
                |--------------------------------------------------------------------------
                */

                currentConversationPage =
                    1;


                lastConversationPage =
                    1;


                await loadConversations();


            } catch (error) {

                console.error(
                    'Delete Error:',
                    error
                );


                alert(
                    'Error: ' +
                    error.message
                );

            }

        }


        /* ============================================================
           OPEN CONVERSATION
        ============================================================ */

        async function openConversation(id) {

            conversationId =
                id;


            localStorage.setItem(
                'conversation_id',
                conversationId
            );


            currentMessagePage =
                1;


            lastMessagePage =
                1;


            /*
            |--------------------------------------------------------------------------
            | Remove Old Messages
            |--------------------------------------------------------------------------
            */

            const messages =
                chatBody.querySelectorAll(
                    '.message:not(#welcomeMessage):not(#typingMessage)'
                );


            messages.forEach(
                function(message) {

                    message.remove();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Hide Welcome
            |--------------------------------------------------------------------------
            */

            hideWelcome();


            /*
            |--------------------------------------------------------------------------
            | Load Messages
            |--------------------------------------------------------------------------
            */

            await loadMessages();


            /*
            |--------------------------------------------------------------------------
            | Refresh Sidebar
            |--------------------------------------------------------------------------
            */

            await loadConversations();

        }


        /* ============================================================
           LOAD MESSAGES
        ============================================================ */

        async function loadMessages(
            page = 1,
            prepend = false
        ) {

            if (!conversationId) {

                return;

            }


            if (loadingMessages) {

                return;

            }


            loadingMessages = true;


            try {

                const response =
                    await fetch(
                        `/ai/chat/${conversationId}/messages?page=${page}`, {

                            method: 'GET',

                            headers: {
                                'Accept': 'application/json'
                            }

                        }
                    );


                const data =
                    await response.json();


                if (
                    !response.ok ||
                    !data.success
                ) {

                    throw new Error(
                        data.message ||
                        'Unable to load chat history.'
                    );

                }


                const pagination =
                    data.messages;


                currentMessagePage =
                    pagination.current_page;


                lastMessagePage =
                    pagination.last_page;


                if (pagination.total > 0) {

                    hideWelcome();

                }


                /*
                |--------------------------------------------------------------------------
                | Backend Latest First
                |--------------------------------------------------------------------------
                |
                | Reverse so UI shows:
                |
                | Oldest -> Newest
                |
                */

                const messages = [...pagination.data].reverse();


                if (prepend) {

                    messages.forEach(
                        function(message) {

                            addMessageAtTop(
                                message.message,

                                message.role === 'assistant' ?
                                'ai' :
                                'user',

                                message.created_at
                            );

                        }
                    );

                } else {

                    messages.forEach(
                        function(message) {

                            addMessage(
                                message.message,

                                message.role === 'assistant' ?
                                'ai' :
                                'user',

                                message.created_at
                            );

                        }
                    );


                    chatBody.scrollTop =
                        chatBody.scrollHeight;

                }


                /*
                |--------------------------------------------------------------------------
                | Older Messages Button
                |--------------------------------------------------------------------------
                */

                if (
                    currentMessagePage <
                    lastMessagePage
                ) {

                    historyLoader.style.display =
                        'block';

                } else {

                    historyLoader.style.display =
                        'none';

                }

            } catch (error) {

                console.error(
                    'History Error:',
                    error
                );

            } finally {

                loadingMessages = false;

            }

        }


        /* ============================================================
           LOAD OLDER MESSAGES
        ============================================================ */

        loadOlderButton.addEventListener(
            'click',
            async function() {

                if (
                    currentMessagePage >=
                    lastMessagePage
                ) {

                    return;

                }


                const oldScrollHeight =
                    chatBody.scrollHeight;


                await loadMessages(
                    currentMessagePage + 1,
                    true
                );


                const newScrollHeight =
                    chatBody.scrollHeight;


                chatBody.scrollTop =
                    newScrollHeight -
                    oldScrollHeight;

            }
        );


        /* ============================================================
           SEND MESSAGE
        ============================================================ */

        chatForm.addEventListener(
            'submit',
            async function(event) {

                event.preventDefault();


                const question =
                    questionInput.value.trim();


                if (!question) {

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Show User Message
                |--------------------------------------------------------------------------
                */

                addMessage(
                    question,
                    'user'
                );


                hideWelcome();


                questionInput.value =
                    '';


                /*
                |--------------------------------------------------------------------------
                | Show Typing
                |--------------------------------------------------------------------------
                */

                typingMessage.style.display =
                    'flex';


                chatBody.scrollTop =
                    chatBody.scrollHeight;


                sendButton.disabled =
                    true;


                try {

                    const csrfToken =
                        document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        .getAttribute('content');


                    const response =
                        await fetch(
                            '/ai/chat', {

                                method: 'POST',

                                headers: {

                                    'Content-Type': 'application/json',

                                    'Accept': 'application/json',

                                    'X-CSRF-TOKEN': csrfToken

                                },

                                body: JSON.stringify({

                                    question: question,

                                    conversation_id: conversationId

                                })

                            }
                        );


                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Something went wrong.'
                        );

                    }


                    if (data.success) {

                        /*
                        |--------------------------------------------------------------------------
                        | Save Conversation ID
                        |--------------------------------------------------------------------------
                        */

                        conversationId =
                            data.conversation_id;


                        localStorage.setItem(
                            'conversation_id',
                            conversationId
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | AI Response
                        |--------------------------------------------------------------------------
                        */

                        addMessage(
                            data.assistant_message.message,

                            'ai',

                            data.assistant_message.created_at
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Refresh Sidebar
                        |--------------------------------------------------------------------------
                        */

                        await loadConversations();

                    } else {

                        addMessage(
                            'Sorry, I could not process your question.',
                            'ai'
                        );

                    }

                } catch (error) {

                    console.error(
                        'Chat Error:',
                        error
                    );


                    addMessage(
                        'Error: ' +
                        error.message,
                        'ai'
                    );

                } finally {

                    typingMessage.style.display =
                        'none';


                    sendButton.disabled =
                        false;


                    questionInput.focus();

                }

            }
        );


        /* ============================================================
           NEW CHAT
        ============================================================ */

        function startNewChat() {

            localStorage.removeItem(
                'conversation_id'
            );


            conversationId =
                null;


            currentMessagePage =
                1;


            lastMessagePage =
                1;


            /*
            |--------------------------------------------------------------------------
            | Remove Messages
            |--------------------------------------------------------------------------
            */

            const messages =
                chatBody.querySelectorAll(
                    '.message:not(#welcomeMessage):not(#typingMessage)'
                );


            messages.forEach(
                function(message) {

                    message.remove();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Show Welcome
            |--------------------------------------------------------------------------
            */

            showNewChatScreen();


            /*
            |--------------------------------------------------------------------------
            | Refresh Sidebar
            |--------------------------------------------------------------------------
            */

            loadConversations();


            questionInput.focus();

        }


        /* ============================================================
           NEW CHAT BUTTONS
        ============================================================ */

        newChatButton.addEventListener(
            'click',
            startNewChat
        );


        sidebarNewChatButton.addEventListener(
            'click',
            startNewChat
        );


        /* ============================================================
           PAGE LOAD
        ============================================================ */

        document.addEventListener(
            'DOMContentLoaded',
            async function() {

                /*
                |--------------------------------------------------------------------------
                | Load Conversations
                |--------------------------------------------------------------------------
                */

                await loadConversations();


                /*
                |--------------------------------------------------------------------------
                | Load Current Conversation
                |--------------------------------------------------------------------------
                */

                if (conversationId) {

                    await loadMessages();

                }


                questionInput.focus();

            }
        );
    </script>

</body>

</html>
