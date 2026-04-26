<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FCARR</title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
<link rel="manifest" href="/site.webmanifest" />
    <link rel="icon" type="image/png" href="assets/school-logo.png">
  <link rel="stylesheet" href="testing.css">
</head>

<body>

<!-- ================= HERO ================= -->
<section class="hero">
  <header class="navbar">
    <div class="nav-container">

      <div class="logo">
        <img src="/assets/logo.png" alt="FCA Logo" class="logo-img">
        <span>Faith Christian Academy of Rodriguez Rizal, INC.</span>
      </div>

      <nav class="nav-links" id="navLinks">
        <a href="#" class="active">Home</a>
        <a href="#">Services</a>
        <a href="#">Team</a>
        <a href="#">Contact</a>
        <a href="../l/login.php" class="btn-login">Login</a>
      </nav>

      <div class="menu-toggle" id="menuToggle">☰</div>
    </div>
  </header>

  <img class="hero-image" src="/assets/faithbg.png" alt="Faith Christian Academy Building">
  
  <div class="hero-content">
    <div class="glass-card">
      <span class="badge">Welcome to our School</span>
      <h1>Empowering Minds, <br>Building Futures</h1>
      <p>Providing quality Christian education for nursery, elementary, and junior high school students.</p>
      <div class="cta-group">
        <a href="#" class="btn btn-primary">Enroll Now</a>
        <a href="#" class="btn btn-outline">Learn More</a>
      </div>
    </div>
  </div>

  <div class="scroll-down"></div>
</section>

<!-- ================= ABOUT ================= -->
<section class="about_us">

  <div class="row nomargin">
    <h2 class="section_title">About us</h2>
  </div>

  <div class="row row2">

    <div>
      <h2>Lorem ipsum dolor sit amet</h2>
      <p>
        Lorem ipsum dolor sit amet,
        <a href="#">A quality web link</a>
        consectetur adipiscing elit. Ut enim ad minim veniam.
      </p>
    </div>

    <div>
      <figure>
        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/96252/octavector-stock2.jpg">
        <figcaption>A meaningful image caption</figcaption>
      </figure>
    </div>

  </div>
</section>

<!-- ================= SERVICES ================= -->
<section class="services">

  <div class="row nomargin">
    <h2 class="section_title">Our Services</h2>
  </div>

  <div class="row row4">

    <div class="service">
      <h3>
        Lorem ipsum dolor sit amet
        <span class="highlight">highlight wow</span>
      </h3>
    </div>

    <div class="service">
      <p>Service description text goes here.</p>
    </div>

    <div class="service">
      <p>Service description text goes here.</p>
    </div>

    <div class="service">
      <p>Service description text goes here.</p>
    </div>

  </div>
</section>

<!-- ================= TEAM ================= -->
<section class="team">

  <div class="row nomargin">
    <h2 class="section_title white">The Team</h2>
  </div>

  <div class="row row4">

    <div class="profile">
      <figure><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/96252/octavector-stock3.jpg"></figure>
      <p class="profile_name">Staff Name</p>
      <p class="profile_position">Position</p>
    </div>

    <div class="profile">
      <figure><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/96252/octavector-stock3.jpg"></figure>
      <p class="profile_name">Staff Name</p>
      <p class="profile_position">Position</p>
    </div>

    <div class="profile">
      <figure><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/96252/octavector-stock3.jpg"></figure>
      <p class="profile_name">Staff Name</p>
      <p class="profile_position">Position</p>
    </div>

    <div class="profile">
      <figure><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/96252/octavector-stock3.jpg"></figure>
      <p class="profile_name">Staff Name</p>
      <p class="profile_position">Position</p>
    </div>

  </div>
</section>

<!-- ================= CONTACT ================= -->
<section>

  <div class="row nomargin">
    <h2 class="section_title">Get in Touch</h2>
  </div>

  <div class="row row2">

    <div>
      <h2>Contact us anytime</h2>
      <p>We are ready to help you with your project.</p>
    </div>

    <div>

      <form class="contact_form">

        <div>
          <label>Name</label>
          <input type="text">
        </div>

        <div>
          <label>Email</label>
          <input type="email">
        </div>

        <div>
          <label>Message</label>
          <textarea rows="5"></textarea>
        </div>

        <button type="submit">Send</button>

      </form>

    </div>

  </div>
</section>

<!-- ================= FOOTER ================= -->
<footer>

  <div class="row row4">

    <div>
      <h3>Contact</h3>
      <p>123 Street Name</p>
    </div>

    <div>
      <h3>Links</h3>
      <ul>
        <li><a href="#">Terms</a></li>
        <li><a href="#">Privacy</a></li>
      </ul>
    </div>

    <div></div>

    <div>
      <h3>Social</h3>
      <p>Links here</p>
    </div>

  </div>

</footer>

<!-- ================= SCRIPT ================= -->
<script>
const menuToggle = document.getElementById("menuToggle");
const navLinks = document.getElementById("navLinks");

menuToggle.addEventListener("click", () => {
  navLinks.classList.toggle("show");
  
});
</script>
<div class="chat-wrapper">
  <div class="chat-trigger" id="chatTrigger">
    <span class="chat-icon">💬</span>
  </div>

  <div class="chat-window" id="chatWindow">
    <div class="chat-header">
      <div class="chat-header-info">
        <strong>FCARR Support</strong>
        <p>Online • Frequently Asked Questions</p>
      </div>
      <span class="close-chat" id="closeChat">&times;</span>
    </div>
    
    <div class="chat-body" id="chatBody">
      <div class="message bot-msg">
        Mabuhay! Welcome to Faith Christian Academy. How can we help you today?
      </div>
      <div class="suggestions" id="suggestions">
        <button onclick="askFAQ('Enrollment')">How to enroll?</button>
        <button onclick="askFAQ('Requirements')">Admission Requirements</button>
        <button onclick="askFAQ('Location')">Where is the school located?</button>
      </div>
    </div>
  </div>
</div>
<script>
   const chatTrigger = document.getElementById('chatTrigger');
const chatWindow = document.getElementById('chatWindow');
const closeChat = document.getElementById('closeChat');
const chatBody = document.getElementById('chatBody');

// Toggle Chat Window
chatTrigger.onclick = () => {
    chatWindow.style.display = chatWindow.style.display === 'flex' ? 'none' : 'flex';
};

closeChat.onclick = () => {
    chatWindow.style.display = 'none';
};

// FAQ Data
const faqs = {
    'Enrollment': 'You can enroll online via our portal or visit the Registrar\'s office from Monday to Friday, 8:00 AM - 4:00 PM.',
    'Requirements': 'Requirements include: PSA Birth Certificate, Form 138 (Report Card), and Good Moral Certificate.',
    'Location': 'We are located in Rodriguez, Rizal. You can see the full map in our Contact section below.'
};

function askFAQ(topic) {
    // 1. Add User Question
    const userDiv = document.createElement('div');
    userDiv.className = 'message user-msg';
    userDiv.innerText = topic;
    chatBody.appendChild(userDiv);

    // 2. Add Bot Response after a short delay
    setTimeout(() => {
        const botDiv = document.createElement('div');
        botDiv.className = 'message bot-msg';
        botDiv.innerText = faqs[topic];
        chatBody.appendChild(botDiv);
        
        // Auto scroll to bottom
        chatBody.scrollTop = chatBody.scrollHeight;
    }, 500);
} 
</script>
</body>         
</html>