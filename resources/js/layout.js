const body = document.querySelector("body");
        sidebar = body.querySelector(".sidebar");
        toggle =  body.querySelector(".toggle");
        searchBtn = body.querySelector(".search-box");
        modeSwitch = body.querySelector(".toggle-switch");
        modeText =  body.querySelector(".mode-text");


        toggle.addEventListener("click", () =>{
            sidebar.classList.toggle("close");
        });

        searchBtn.addEventListener("click", () =>{
            sidebar.classList.remove("close");
        });

        modeSwitch.addEventListener("click", () =>{
            body.classList.toggle("dark");

            if(body.classList.contains("dark")){
                modeText.innerText = "Light Mode";
            }else{
                modeText.innerText = "Dark Mode";
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const sideLinks = document.querySelectorAll('.sidebar .menu-links .nav-link');
        
            sideLinks.forEach(item => {
                item.addEventListener('click', () => {
                    sideLinks.forEach(link => link.classList.remove('active'));
                    item.classList.add('active');
                });
            });
        });
        
        
