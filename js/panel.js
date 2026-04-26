const body = document.querySelector("body"),
      modeToggle = body.querySelector(".mode-toggle"),
      sidebar = body.querySelector("nav"), // Added 'const'
      sidebarToggle = body.querySelector(".sidebar-toggle"); // Added 'const'
let getMode = localStorage.getItem("mode");
if (getMode && getMode === "dark") {
  body.classList.toggle("dark");
}

let getStatus = localStorage.getItem("status");
if (getStatus && getStatus === "close") {
  sidebar.classList.toggle("close");
}

modeToggle.addEventListener("click", () => {
  body.classList.toggle("dark");
  if (body.classList.contains("dark")) {
    localStorage.setItem("mode", "dark");
  } else {
    localStorage.setItem("mode", "light");
  }
});

sidebarToggle.addEventListener("click", () => {
  sidebar.classList.toggle("close");
  if (sidebar.classList.contains("close")) {
    localStorage.setItem("status", "close");
  } else {
    localStorage.setItem("status", "open");
  }
});

// Set active nav item based on current page URL
const navItems = document.querySelectorAll(".nav-links li");
const currentPath = window.location.pathname.replace(/\/+/g, "/").split("/").pop();
navItems.forEach((item) => {
  const link = item.querySelector("a");
  if (!link) return;
  const linkPath = link.getAttribute("href");
  if (!linkPath || linkPath === "#") return;

  const resolved = new URL(linkPath, window.location.href).pathname.split("/").pop();
  if (resolved === currentPath) {
    item.classList.add("active");
  } else {
    item.classList.remove("active");
  }
});
