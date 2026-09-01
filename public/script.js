const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1 });
  
  document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
  
  document.getElementById('rsvp-form').addEventListener('submit', (e) => {
    e.preventDefault();
    alert('Спасибо! Мы записали вас.');
  });
  