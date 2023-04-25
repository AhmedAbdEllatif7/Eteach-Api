<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>CodePen - Chat UI Responsive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Lato'><link rel="stylesheet" href="{{URL::asset('style.css')}}">

</head>
<body>
<!-- partial:index.partial.html -->
<div class="--dark-theme" id="chat">
    <div class="chat__conversation-board">
        <div class="chat__conversation-board__message-container">
            <div class="chat__conversation-board__message__person">
                <div class="chat__conversation-board__message__person__avatar"><img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Monika Figi"/></div><span class="chat__conversation-board__message__person__nickname">Monika Figi</span>
            </div>
            <form method="get" action="{{url('test')}}" >
            <div class="chat__conversation-board__message__context">
                <div class="chat__conversation-board__message__bubble"> <span>Somewhere stored deep, deep in my memory banks is the phrase &quot;It really whips the llama's ass&quot;.</span></div>
            </div>
            <div class="chat__conversation-board__message__options">
                <button class="btn-icon chat__conversation-board__message__option-button option-item emoji-button">
                    <svg class="feather feather-smile sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                </button>
                <button class="btn-icon chat__conversation-board__message__option-button option-item more-button">
                    <svg class="feather feather-more-horizontal sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="19" cy="12" r="1"></circle>
                        <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                </button>
            </div>
        </div>
        <div class="chat__conversation-board__message-container">
            <div class="chat__conversation-board__message__person">
                <div class="chat__conversation-board__message__person__avatar"><img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Thomas Rogh"/></div><span class="chat__conversation-board__message__person__nickname">Thomas Rogh</span>
            </div>
            <div class="chat__conversation-board__message__context">
                <div class="chat__conversation-board__message__bubble"> <span>Think the guy that did the voice has a Twitter?</span></div>
            </div>
            <div class="chat__conversation-board__message__options">
                <button class="btn-icon chat__conversation-board__message__option-button option-item emoji-button">
                    <svg class="feather feather-smile sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                </button>
                <button class="btn-icon chat__conversation-board__message__option-button option-item more-button">
                    <svg class="feather feather-more-horizontal sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="19" cy="12" r="1"></circle>
                        <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                </button>
            </div>
        </div>
        <div class="chat__conversation-board__message-container">
            <div class="chat__conversation-board__message__person">
                <div class="chat__conversation-board__message__person__avatar"><img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Monika Figi"/></div><span class="chat__conversation-board__message__person__nickname">Monika Figi</span>
            </div>
            <div class="chat__conversation-board__message__context">
                <div class="chat__conversation-board__message__bubble"> <span>WE MUST FIND HIM!!</span></div>
                <div class="chat__conversation-board__message__bubble"> <span>Wait ...</span></div>
            </div>
            <div class="chat__conversation-board__message__options">
                <button class="btn-icon chat__conversation-board__message__option-button option-item emoji-button">
                    <svg class="feather feather-smile sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                </button>
                <button class="btn-icon chat__conversation-board__message__option-button option-item more-button">
                    <svg class="feather feather-more-horizontal sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="19" cy="12" r="1"></circle>
                        <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                </button>
            </div>
        </div>
        <div class="chat__conversation-board__message-container reversed">
            <div class="chat__conversation-board__message__person">
                <div class="chat__conversation-board__message__person__avatar"><img src="https://randomuser.me/api/portraits/men/9.jpg" alt="Dennis Mikle"/></div><span class="chat__conversation-board__message__person__nickname">Dennis Mikle</span>
            </div>
            <div class="chat__conversation-board__message__context">
                <div class="chat__conversation-board__message__bubble"> <span>Winamp's still an essential.</span></div>
            </div>
            <div class="chat__conversation-board__message__options">
                <button class="btn-icon chat__conversation-board__message__option-button option-item emoji-button">
                    <svg class="feather feather-smile sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                </button>
                <button class="btn-icon chat__conversation-board__message__option-button option-item more-button">
                    <svg class="feather feather-more-horizontal sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="19" cy="12" r="1"></circle>
                        <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div class="chat__conversation-panel">
        <div class="chat__conversation-panel__container">


            <input name="message" class="chat__conversation-panel__input panel-item" placeholder="Type a message..."/>
            <button class="chat__conversation-panel__button panel-item btn-icon send-message-button" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" data-reactid="1036">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
            </form>
        </div>
    </div>
</div>
<!-- partial -->
<script  src="{{URL::asset('script.js')}}"></script>

</body>
</html>
