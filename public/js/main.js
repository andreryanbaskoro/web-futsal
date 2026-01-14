/**
 * FUSTAL ACR - Main JavaScript
 * Handles navigation, animations, and interactions
 */

document.addEventListener("DOMContentLoaded", function () {
    // ===== Navbar Scroll Effect =====
    const navbar = document.getElementById("navbar");
    const navbarToggle = document.getElementById("navbarToggle");
    const navbarMenu = document.getElementById("navbarMenu");

    // Scroll effect for navbar
    function handleScroll() {
        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    }

    window.addEventListener("scroll", handleScroll);
    handleScroll(); // Initial check

    // ===== Mobile Menu Toggle =====
    if (navbarToggle && navbarMenu) {
        navbarToggle.addEventListener("click", function () {
            this.classList.toggle("active");
            navbarMenu.classList.toggle("active");
            document.body.style.overflow = navbarMenu.classList.contains(
                "active"
            )
                ? "hidden"
                : "";
        });

        // Close menu when clicking on a link
        const navLinks = navbarMenu.querySelectorAll(".nav-link");
        navLinks.forEach((link) => {
            link.addEventListener("click", function () {
                navbarToggle.classList.remove("active");
                navbarMenu.classList.remove("active");
                document.body.style.overflow = "";
            });
        });

        // Close menu when clicking outside
        document.addEventListener("click", function (e) {
            if (
                !navbar.contains(e.target) &&
                navbarMenu.classList.contains("active")
            ) {
                navbarToggle.classList.remove("active");
                navbarMenu.classList.remove("active");
                document.body.style.overflow = "";
            }
        });
    }

    // ===== Smooth Scroll for Anchor Links =====
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            const href = this.getAttribute("href");
            if (href === "#") return;

            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const offsetTop = target.offsetTop - 80;
                window.scrollTo({
                    top: offsetTop,
                    behavior: "smooth",
                });
            }
        });
    });

    // ===== Animate on Scroll =====
    const animatedElements = document.querySelectorAll(".animate-fadeInUp");

    function checkAnimation() {
        animatedElements.forEach((el) => {
            const elementTop = el.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (elementTop < windowHeight - 100) {
                el.style.opacity = "1";
                el.style.transform = "translateY(0)";
            }
        });
    }

    // Set initial state
    animatedElements.forEach((el) => {
        el.style.opacity = "0";
        el.style.transform = "translateY(30px)";
        el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    });

    window.addEventListener("scroll", checkAnimation);
    checkAnimation(); // Initial check

    // ===== Testimonial Slider =====
    const testimonials = [
        {
            quote: "Lapangan bersih, rumput bagus, dan booking sangat mudah! Gak perlu telepon-telepon, langsung booking dari HP. Recommended banget untuk yang mau main futsal bareng teman!",
            name: "Ahmad Fadillah",
            role: "Tim Futsal Garuda FC",
            avatar: "https://i.pravatar.cc/100?img=11",
            rating: 5,
        },
        {
            quote: "Sudah 2 tahun langganan di sini. Pelayanannya ramah, lapangannya terawat, dan yang paling penting booking-nya gampang banget. Tinggal pilih, bayar, selesai!",
            name: "Budi Santoso",
            role: "Tim Futsal Elang Jaya",
            avatar: "https://i.pravatar.cc/100?img=12",
            rating: 5,
        },
        {
            quote: "Fasilitasnya lengkap, ada ruang ganti, kamar mandi bersih, dan parkir luas. Harga juga terjangkau untuk kualitas lapangan sebagus ini. Top!",
            name: "Reza Pratama",
            role: "Anggota Komunitas Futsal",
            avatar: "https://i.pravatar.cc/100?img=13",
            rating: 5,
        },
    ];

    const testimonialDots = document.querySelectorAll(".testimonial-dot");
    let currentTestimonial = 0;

    function updateTestimonial(index) {
        const card = document.getElementById("testimonial-1");
        if (!card) return;

        const t = testimonials[index];
        const stars = '<i class="fas fa-star"></i>'.repeat(t.rating);

        card.innerHTML = `
      <p class="testimonial-quote">${t.quote}</p>
      <div class="testimonial-author">
        <div class="testimonial-avatar">
          <img src="${t.avatar}" alt="${t.name}">
        </div>
        <div class="testimonial-info">
          <div class="testimonial-name">${t.name}</div>
          <div class="testimonial-role">${t.role}</div>
          <div class="testimonial-rating">${stars}</div>
        </div>
      </div>
    `;

        testimonialDots.forEach((dot, i) => {
            dot.classList.toggle("active", i === index);
        });
    }

    testimonialDots.forEach((dot, index) => {
        dot.addEventListener("click", () => {
            currentTestimonial = index;
            updateTestimonial(index);
        });
    });

    // Auto-rotate testimonials
    setInterval(() => {
        currentTestimonial = (currentTestimonial + 1) % testimonials.length;
        updateTestimonial(currentTestimonial);
    }, 5000);

    // ===== Active Nav Link =====
    const sections = document.querySelectorAll("section[id]");
    const navLinksAll = document.querySelectorAll(".nav-link");

    function highlightNav() {
        const scrollPos = window.scrollY + 100;

        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute("id");

            if (
                scrollPos >= sectionTop &&
                scrollPos < sectionTop + sectionHeight
            ) {
                navLinksAll.forEach((link) => {
                    link.classList.remove("active");
                    if (
                        link.getAttribute("href") === `#${sectionId}` ||
                        (sectionId === "hero" &&
                            link.getAttribute("href") === "index.html")
                    ) {
                        link.classList.add("active");
                    }
                });
            }
        });
    }

    window.addEventListener("scroll", highlightNav);

    // ===== Gallery Lightbox (if on gallery page) =====
    const galleryItems = document.querySelectorAll(
        ".gallery-item:not(.gallery-more)"
    );

    galleryItems.forEach((item) => {
        item.addEventListener("click", function () {
            const img = this.querySelector("img");
            if (img) {
                openLightbox(img.src, img.alt);
            }
        });
    });

    function openLightbox(src, alt) {
        const lightbox = document.createElement("div");
        lightbox.className = "lightbox";
        lightbox.innerHTML = `
      <div class="lightbox-backdrop"></div>
      <div class="lightbox-content">
        <button class="lightbox-close">&times;</button>
        <img src="${src}" alt="${alt}">
        <p class="lightbox-caption">${alt}</p>
      </div>
    `;

        // Add lightbox styles dynamically
        lightbox.style.cssText = `
      position: fixed;
      inset: 0;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
    `;

        const backdrop = lightbox.querySelector(".lightbox-backdrop");
        backdrop.style.cssText = `
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.9);
    `;

        const content = lightbox.querySelector(".lightbox-content");
        content.style.cssText = `
      position: relative;
      max-width: 90%;
      max-height: 90%;
      text-align: center;
    `;

        const closeBtn = lightbox.querySelector(".lightbox-close");
        closeBtn.style.cssText = `
      position: absolute;
      top: -40px;
      right: 0;
      font-size: 32px;
      color: white;
      background: none;
      border: none;
      cursor: pointer;
    `;

        const lightboxImg = lightbox.querySelector("img");
        lightboxImg.style.cssText = `
      max-width: 100%;
      max-height: 80vh;
      border-radius: 8px;
    `;

        const caption = lightbox.querySelector(".lightbox-caption");
        caption.style.cssText = `
      color: white;
      margin-top: 16px;
      font-size: 14px;
    `;

        document.body.appendChild(lightbox);
        document.body.style.overflow = "hidden";

        // Close handlers
        closeBtn.addEventListener("click", closeLightbox);
        backdrop.addEventListener("click", closeLightbox);
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape") closeLightbox();
        });

        function closeLightbox() {
            lightbox.remove();
            document.body.style.overflow = "";
        }
    }

    // ===== Form Validation Helper =====
    window.validateForm = function (form) {
        let isValid = true;
        const inputs = form.querySelectorAll(
            "input[required], select[required], textarea[required]"
        );

        inputs.forEach((input) => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add("error");
                input.style.borderColor = "var(--color-error)";
            } else {
                input.classList.remove("error");
                input.style.borderColor = "";
            }
        });

        return isValid;
    };

    // ===== Format Currency =====
    window.formatCurrency = function (amount) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(amount);
    };

    // ===== Date Formatter =====
    window.formatDate = function (date) {
        return new Intl.DateTimeFormat("id-ID", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        }).format(new Date(date));
    };

    console.log("🎾 FUSTAL ACR - Ready to play!");
});

document.addEventListener("DOMContentLoaded", () => {
  const profileToggle = document.getElementById("profileToggle");
  const profileMenu = document.getElementById("profileMenu");

  if (profileToggle) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileMenu.classList.toggle("show");
      profileToggle.classList.toggle("active");
    });
  }

  // Tutup dropdown saat klik di luar
  document.addEventListener("click", () => {
    profileMenu.classList.remove("show");
    profileToggle.classList.remove("active");
  });
});

