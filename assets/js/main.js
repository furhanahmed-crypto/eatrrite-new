(function () {
    /* Mobile nav */
    var toggle = document.querySelector(".nav-toggle");
    var nav = document.querySelector(".site-nav");
    if (toggle && nav) {
        toggle.addEventListener("click", function () {
            nav.classList.toggle("is-open");
            toggle.classList.toggle("is-open");
        });
    }

    /* FAQ accordion */
    var faqs = document.querySelectorAll(".faq-item");
    faqs.forEach(function (item) {
        var button = item.querySelector(".faq-question");
        if (!button) return;
        button.addEventListener("click", function () {
            var open = item.classList.contains("is-open");
            faqs.forEach(function (other) {
                other.classList.remove("is-open");
            });
            if (!open) item.classList.add("is-open");
        });
    });

    /* Sticky header */
    var header = document.querySelector(".site-header");
    if (header) {
        var onScroll = function () {
            header.classList.toggle("is-sticky", window.scrollY > 20);
        };
        window.addEventListener("scroll", onScroll, { passive: true });
        onScroll();
    }

    /* Hero slider */
    var slides = document.querySelectorAll(".hero-slide");
    if (slides.length > 1) {
        var currentSlide = 0;
        setInterval(function () {
            slides[currentSlide].classList.remove("active");
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add("active");
        }, 5000);
    }

    /* Video modal */
    var videoModal = document.getElementById("videoModal");
    var videoFrame = document.getElementById("videoFrame");
    var videoError = document.getElementById("videoError");

    function openVideoModal() {
        if (!videoModal) return;
        videoModal.classList.add("is-open");
        videoModal.setAttribute("aria-hidden", "false");
        document.body.classList.add("modal-open");

        if (videoFrame) {
            var badSrc = videoFrame.getAttribute("data-src") || "";
            videoFrame.src = badSrc;
            /* Intentional invalid YouTube id — show error UI in place of video */
            if (videoError) {
                videoError.hidden = false;
            }
            videoFrame.addEventListener(
                "load",
                function () {
                    if (videoError) videoError.hidden = false;
                },
                { once: true }
            );
        }
    }

    function closeVideoModal() {
        if (!videoModal) return;
        videoModal.classList.remove("is-open");
        videoModal.setAttribute("aria-hidden", "true");
        document.body.classList.remove("modal-open");
        if (videoFrame) videoFrame.src = "";
        if (videoError) videoError.hidden = true;
    }

    document.querySelectorAll(".js-open-video").forEach(function (el) {
        el.addEventListener("click", function (e) {
            e.preventDefault();
            openVideoModal();
        });
    });
    var playWrap = document.querySelector(".hero-play-wrap");
    if (playWrap) {
        playWrap.addEventListener("click", function (e) {
            if (e.target.closest(".js-open-video")) return;
            e.preventDefault();
            openVideoModal();
        });
    }
    document.querySelectorAll(".js-close-video").forEach(function (el) {
        el.addEventListener("click", closeVideoModal);
    });
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeVideoModal();
    });

    /**
     * Split heading into words > letters.
     * Overflow is on each WORD (not the whole line) so multi-line titles are never clipped.
     */
    function splitHeading(el) {
        if (!el || el.dataset.split === "1") return;
        var text = el.textContent.trim();
        el.setAttribute("aria-label", text);
        el.innerHTML = "";

        var line = document.createElement("div");
        line.className = "split-line";

        text.split(/\s+/).forEach(function (word) {
            var wordEl = document.createElement("span");
            wordEl.className = "split-word";

            Array.from(word).forEach(function (char) {
                var charEl = document.createElement("span");
                charEl.className = "split-char";
                charEl.textContent = char;
                wordEl.appendChild(charEl);
            });

            line.appendChild(wordEl);
            line.appendChild(document.createTextNode(" "));
        });

        el.appendChild(line);
        el.dataset.split = "1";
        el.classList.add("is-ready");
    }

    function revealUp(elements, options) {
        var list = gsap.utils.toArray(elements).filter(function (el) {
            return el && el.dataset.revealed !== "1";
        });
        if (!list.length) return;

        list.forEach(function (el) {
            el.dataset.revealed = "1";
        });

        var opts = options || {};
        gsap.fromTo(
            list,
            {
                autoAlpha: 0,
                y: opts.y != null ? opts.y : 40
            },
            {
                autoAlpha: 1,
                y: 0,
                duration: opts.duration || 0.8,
                ease: "power3.out",
                stagger: opts.stagger != null ? opts.stagger : 0.1,
                clearProps: "transform",
                scrollTrigger: {
                    trigger: opts.trigger || list[0],
                    start: opts.start || "top 90%",
                    toggleActions: "play none none none",
                    once: true
                }
            }
        );
    }

    function initGsapAnimations() {
        if (typeof gsap === "undefined") {
            /* If CDN failed, make sure nothing stays hidden */
            document.querySelectorAll(".split-title.is-ready .split-char").forEach(function (c) {
                c.style.opacity = "1";
                c.style.transform = "none";
            });
            return;
        }

        if (typeof ScrollTrigger !== "undefined") {
            gsap.registerPlugin(ScrollTrigger);
        }

        /* ---------- Letter-split headings ---------- */
        document.querySelectorAll(".split-title").forEach(function (heading) {
            splitHeading(heading);
            var chars = heading.querySelectorAll(".split-char");
            if (!chars.length) return;

            var isHero = heading.classList.contains("hero-title");

            gsap.fromTo(
                chars,
                { yPercent: 100, autoAlpha: 0 },
                {
                    yPercent: 0,
                    autoAlpha: 1,
                    duration: 0.65,
                    ease: "power3.out",
                    stagger: 0.02,
                    delay: isHero ? 0.15 : 0,
                    clearProps: "transform",
                    scrollTrigger: isHero
                        ? undefined
                        : {
                              trigger: heading,
                              start: "top 88%",
                              toggleActions: "play none none none",
                              once: true
                          }
                }
            );
        });

        /* ---------- Hero content ---------- */
        var heroContent = document.querySelector(".hero-content");
        if (heroContent) {
            gsap.fromTo(
                heroContent.querySelectorAll(".eyebrow, .pill-label, .hero-text, .hero-actions"),
                { autoAlpha: 0, y: 28 },
                {
                    autoAlpha: 1,
                    y: 0,
                    duration: 0.8,
                    ease: "power3.out",
                    stagger: 0.1,
                    delay: 0.35,
                    clearProps: "transform"
                }
            );
        }

        /* ---------- Labels / leads (each triggers on its own) ---------- */
        document.querySelectorAll(".pill-label, .eyebrow, .section-lead").forEach(function (el) {
            if (el.closest(".hero-content") || el.closest(".hero-slider")) return;
            revealUp(el, { trigger: el, y: 24, stagger: 0, duration: 0.7 });
        });

        /* ---------- Card grids ---------- */
        [
            [".services-nourio-grid", ".svc-card"],
            [".services-grid", ".card"],
            [".why-grid", ".card"],
            [".programs-grid", ".card, .program-card"],
            [".process-grid", ".process-card"],
            [".testimonial-grid", ".testimonial-card"],
            [".faq-wrap", ".faq-item"]
        ].forEach(function (pair) {
            document.querySelectorAll(pair[0]).forEach(function (grid) {
                revealUp(grid.querySelectorAll(pair[1]), {
                    trigger: grid,
                    y: 48,
                    stagger: 0.12,
                    duration: 0.85,
                    start: "top 92%"
                });
            });
        });

        /* ---------- About / split media ---------- */
        document.querySelectorAll(".about-photos").forEach(function (media) {
            revealUp(media, { trigger: media, y: 36, duration: 0.95 });
        });
        document.querySelectorAll(".split > div:not(.about-photos)").forEach(function (copy) {
            var bits = copy.querySelectorAll("p:not(.eyebrow), .mini-list, .btn, .mini-item");
            revealUp(bits, { trigger: copy, y: 28, stagger: 0.08, start: "top 85%" });
        });

        /* ---------- CTA ---------- */
        document.querySelectorAll(".cta-box").forEach(function (box) {
            revealUp(box, { trigger: box, y: 40, duration: 0.9 });
        });

        /* ---------- Forms ---------- */
        document.querySelectorAll(".contact-grid > *").forEach(function (col) {
            revealUp(col, { trigger: col, y: 32, duration: 0.85 });
        });

        /* ---------- Page banner ---------- */
        var banner = document.querySelector(".page-banner .container");
        if (banner) {
            gsap.fromTo(
                banner.children,
                { autoAlpha: 0, y: 30 },
                {
                    autoAlpha: 1,
                    y: 0,
                    duration: 0.8,
                    stagger: 0.1,
                    clearProps: "transform"
                }
            );
        }

        /* ---------- Footer ---------- */
        var footerGrid = document.querySelector(".footer-grid");
        if (footerGrid) {
            revealUp(footerGrid.children, {
                trigger: footerGrid,
                y: 28,
                stagger: 0.08,
                start: "top 95%"
            });
        }

        /* Failsafe: only force-show elements already in/near the viewport if stuck */
        setTimeout(function () {
            var selectors =
                ".card, .svc-card, .process-card, .testimonial-card, .faq-item, .cta-box, .section-lead, .about-photos, .pill-label, .eyebrow, .contact-grid > *, .footer-grid > *, .split-char";
            document.querySelectorAll(selectors).forEach(function (el) {
                var rect = el.getBoundingClientRect();
                var nearView = rect.top < window.innerHeight && rect.bottom > 0;
                if (!nearView) return;
                if (parseFloat(getComputedStyle(el).opacity) === 0) {
                    gsap.set(el, { autoAlpha: 1, y: 0, yPercent: 0, clearProps: "transform" });
                }
            });
            if (typeof ScrollTrigger !== "undefined") ScrollTrigger.refresh();
        }, 2000);

        if (typeof ScrollTrigger !== "undefined") ScrollTrigger.refresh();
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initGsapAnimations);
    } else {
        initGsapAnimations();
    }
})();
