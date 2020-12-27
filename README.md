# Project Planner
This is a project planner web application I've been working on.

The backend uses the PHP framework **Laravel** and it runs on this domain: "todoapp.loc/"

The frontend uses the **VueJS** framework and it runs on "localhost:8080/"

## Authentication
The app includes an authentication system with which users can login or register. JWT Auth allows for a secure authentication and interaction between the frontend and backend. Tokens have an expiration time of 15 minutes. Once the expiration time is over, the token will be invalid, any action with that token will be denied and the user will be automatically signed out.

## Packages installed:
**Tailwind CSS** - for easier and prettier web design

**jwt-auth** - creating a secure connection between the backend and the frontend
