import './bootstrap';
import Search from './live-search';
import Chat from './chat';
import Profile from './profile';

if (document.querySelector(".profile-nav")) {
    new Profile();
}

//only will appear search if there is something to search for
if (document.querySelector(".header-search-icon")) {
    new Search(); //if there is elemetn with that class 
}

if (document.querySelector(".header-chat-icon")) {
    new Chat();
} 