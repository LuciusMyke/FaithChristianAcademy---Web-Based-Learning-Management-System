function openTab(tabName){

let tabs = document.querySelectorAll(".tab-content");
let buttons = document.querySelectorAll(".tab-btn");

tabs.forEach(tab => tab.classList.remove("active"));
buttons.forEach(btn => btn.classList.remove("active"));

document.getElementById(tabName).classList.add("active");
event.target.classList.add("active");

}


document.getElementById("searchInput")?.addEventListener("keyup", function(){

let filter = this.value.toLowerCase();
let rows = document.querySelectorAll("#gradeTable tbody tr");

rows.forEach(row => {

let text = row.innerText.toLowerCase();

row.style.display = text.includes(filter) ? "" : "none";

});

});


window.onload = function(){

let popup = document.getElementById("successPopup");

if(popup){

setTimeout(()=>{
popup.style.opacity = "0";
},3000);

}

}