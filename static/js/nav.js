// Initialize Lenis
const lenis = new Lenis({
  smooth: true,
  direction: "vertical",
  gestureDirection: "vertical",
  smoothTouch: false,
  touchMultiplier: 2,
});

// GSAP integration
function raf(time) {
  lenis.raf(time);
  requestAnimationFrame(raf);
}
requestAnimationFrame(raf);

// Optional: Hook Lenis scroll into ScrollTrigger
lenis.on('scroll', () => {
  ScrollTrigger.update();
});

// Navbar shrink logic
function navbarShrink(scrollY) {
  const navbar = document.querySelector('#mainNav');
  if (!navbar) return;
  if (scrollY > 0) {
    navbar.classList.add('navbar-shrink');
  } else {
    navbar.classList.remove('navbar-shrink');
  }
}

// Track scroll position via Lenis
let lastScrollY = 0;
lenis.on('scroll', ({ scroll }) => {
  lastScrollY = scroll;
  navbarShrink(scroll);
});
