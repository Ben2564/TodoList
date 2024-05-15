/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

document.querySelectorAll(".done").forEach(ele => {
    ele.addEventListener("click",() => {
        let url = "http://127.0.0.1:8000/todo/done/" + ele.parentNode.querySelector(".id").textContent
        fetch(url ,{
                method:"get"
            }
        ).then(function(res){
            return res.json()
        }).then(function(value){
            console.log(value[0])
            if(value[1] == true){
                ele.textContent = "fait"
            } else{
                ele.textContent = "à faire"
            }
        })
    })
})

let tab = document.querySelector("tbody")
const search = document.querySelector('#search');
search.addEventListener("keyup",function(){
    tab.innerHTML = ""
    fetch("http://127.0.0.1:8000/todo/search", {
        body: JSON.stringify({ search: search.value }),
        method:"post",
        headers: {
            "Content-Type": "application/json"
        }
    }).then(function(res){
        return res.json()
    }).then(function(data){
        data.forEach(ele => {
            let tr = document.createElement("tr");
            let tdId = document.createElement("td");
            tdId.className = "id";
            tdId.textContent = ele.id;
            tr.appendChild(tdId);

            let tdUserId = document.createElement("td");
            tdUserId.textContent = ele.name;
            tr.appendChild(tdUserId);

            let tdDescription = document.createElement("td");
            tdDescription.textContent = ele.Description;
            tr.appendChild(tdDescription);

            let tdStatus = document.createElement("td");
            tdStatus.className = "done";
            tdStatus.textContent = ele.done ? "fait" : "à faire";
            tr.appendChild(tdStatus);

            let tdActions = document.createElement("td");
            let showLink = document.createElement("a");
            showLink.href = "/todo/"+ ele.id;
            showLink.textContent = "show";
            tdActions.appendChild(showLink);

            let editLink = document.createElement("a");
            editLink.href = "/todo/"+ ele.id +"/edit";
            editLink.textContent = "edit";
            tdActions.appendChild(editLink);
            tr.appendChild(tdActions);

            tab.appendChild(tr);
        })
    })
})

const checkbox = document.querySelector('#checkbox');
checkbox.addEventListener("change",function(){
    if (checkbox.checked){
        checkbox.value = true;
    } else{
        checkbox.value = false;
    }
    tab.innerHTML = ""
    fetch("http://127.0.0.1:8000/todo/check", {
        body: JSON.stringify({ check: checkbox.value }),
        method:"post",
        headers: {
            "Content-Type": "application/json"
        }
    }).then(function(res){
        return res.json()
    }).then(function(data){
        data.forEach(ele => {
            let tr = document.createElement("tr");
            let tdId = document.createElement("td");
            tdId.className = "id";
            tdId.textContent = ele.id;
            tr.appendChild(tdId);

            let tdUserId = document.createElement("td");
            tdUserId.textContent = ele.name;
            tr.appendChild(tdUserId);

            let tdDescription = document.createElement("td");
            tdDescription.textContent = ele.Description;
            tr.appendChild(tdDescription);

            let tdStatus = document.createElement("td");
            tdStatus.className = "done";
            tdStatus.textContent = ele.done ? "fait" : "à faire";
            tr.appendChild(tdStatus);

            let tdActions = document.createElement("td");
            let showLink = document.createElement("a");
            showLink.href = "/todo/"+ ele.id;
            showLink.textContent = "show";
            tdActions.appendChild(showLink);

            let editLink = document.createElement("a");
            editLink.href = "/todo/"+ ele.id +"/edit";
            editLink.textContent = "edit";
            tdActions.appendChild(editLink);
            tr.appendChild(tdActions);

            tab.appendChild(tr);
        })
    })
})