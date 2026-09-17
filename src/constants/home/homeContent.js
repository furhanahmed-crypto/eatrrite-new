import { programs } from "@/constants/programs/programs";

const serviceIcons = ["Wheat", "Droplet", "Leaf", "Venus"];

export const homeContent = [
  {
    name: "hero-section",
    pill: "Holistic Nutrition Coaching",
    title: "Lose Weight Without Losing Your Dal-Chawal.",
    text: "Real nutrition isn't about starvation or fear — it's about learning how to Eat Rrite. Science-backed, rooted in the food you already love, and built to last longer than 30 days.",
    slides: ["/images/hero/carousel-1.png", "/images/hero/carousel-2.png"],
    primaryCta: { label: "Get Consultation", href: "/appointment" },
    secondaryCta: { label: "View Our Programs", href: "/programs" },
    stats: [
      { value: "8", label: "Years of Practice", icon: "years" },
      { value: "500+", label: "Lives Transformed", icon: "lives" },
      { value: "4.8★", label: "Google Rating", icon: "rating" },
      {
        value: "Stories",
        label: "Real Client Stories",
        href: "#testimonials",
        icon: "stories",
      },
    ],
  },
  {
    name: "services-section",
    pill: "Programs",
    title: "Built around your reports, not a template",
    lead: "Whether it's weight loss, diabetes reversal, gut health or hormonal balance — every Eat Rrite program starts with a consultation. Choose a 30-day reset, a 90-day journey, or a 180-day commitment.",
    programs: programs.map((program, i) => ({
      ...program,
      icon: serviceIcons[i] || "Heart",
    })),
    cta: { label: "Explore Our Programs", href: "/programs" },
  },
  {
    name: "marquee-section",
    items: [
      "Since 2018",
      "Food as Medicine",
      "Traditions",
      "Minimalism",
      "Sustainability",
      "Yoga & Counselling",
      "Personalized Plans",
      "Holistic Wellness",
    ],
  },
  {
    name: "about-home-section",
    pill: "Founder Spotlight",
    title: "Meet Mukta Patil",
    lead: "Founder Mukta Patil has spent 8 years building Eat Rrite out of her own health journey — from a personal health crisis to a nutrition practice trusted by hundreds of families across Hyderabad and Dehradun.",
    text: "Her approach is simple: food should never be feared. It should be understood.",
    quote:
      "Your body is a living marvel — take care of it, and it will take care of you when you are old.",
    cite: "— Mukta Patil (founder)",
    image: "/images/about/founder.jpg",
    sideImage: "/images/about/bowl.jpg",
    cta: { label: "Read Mukta's Full Story", href: "/about" },
  },
  {
    name: "why-choose-section",
    pill: "Why Choose Us",
    title: "Where food becomes medicine",
    items: [
      {
        num: "01",
        icon: "Bowl",
        title: "Where Food Becomes Medicine",
        text: "No crash diets, no starvation. You keep eating your own traditional cuisine — idli-dosa-sambar, rice, paratha and sabzi — we just teach you how to balance it.",
      },
      {
        num: "02",
        icon: "Heart",
        title: "Built for Life, Not 30 Days",
        text: "We don't fight against time to hit a number on the scale — we help you reprogram how you think about food. Learn to Eat Rrite, for life, starting in 30 days.",
      },
      {
        num: "03",
        icon: "Flask",
        title: "Science First, Always",
        text: "We don't suggest a food or strategy just because it's \"ancient wisdom\" or trending. Everything we recommend has both traditional grounding and science behind it.",
      },
      {
        num: "04",
        icon: "Spa",
        title: "Whole-Person Wellbeing",
        text: "Nutrition is your foundation, yoga and movement build physical wellbeing, and counselling helps you unburden your mind. All three pillars have to align.",
      },
      {
        num: "05",
        icon: "Clipboard",
        title: "No Guesswork, Ever",
        text: "No client is onboarded without proper tests. We look at what's actually happening in your body, coordinate with a doctor when needed, and refer you if you don't have one.",
      },
    ],
  },
  {
    name: "process-section",
    pill: "Working Process",
    title: "A simple process for better health",
    steps: [
      {
        num: "01",
        title: "Health Assessment",
        text: "We begin with your history, reports and lifestyle so the plan is built around you.",
      },
      {
        num: "02",
        title: "Personalized Plan",
        text: "A unique diet and wellness program aligned to your culture, goals and routine.",
      },
      {
        num: "03",
        title: "Habit Building",
        text: "Practical weekly guidance so healthy eating becomes a routine, not a restriction.",
      },
      {
        num: "04",
        title: "Ongoing Support",
        text: "Monitoring, adjustments and coaching to help you maintain the results you earn.",
      },
    ],
  },
  {
    name: "programs-section",
    pill: "Our Programs",
    title: "Nutrition programs built around you",
    lead: "Every program starts with a consultation call that looks at your reports, history and lifestyle — then we build a 30, 90 or 180-day plan around food you already eat.",
    programs,
    cta: { label: "Get Consultation", href: "/appointment" },
  },
  {
    name: "faq-section",
    pill: "FAQs",
    title: "Frequently asked questions",
    image: "/images/faq/faq-illustration.png",
    items: [
      {
        q: "How do I book a consultation with Eat Rrite?",
        a: "Book your consultation call via the appointment page on this site, or message us on WhatsApp at +91 96398 77483. The consultation is a considered first step before choosing a 30, 90 or 180-day program.",
      },
      {
        q: "Are consultations online, in-person, or both?",
        a: "All Eat Rrite consultations and programs are conducted online, so you can work with Mukta from anywhere — she's based between Hyderabad, Telangana and Dehradun, Uttarakhand.",
      },
      {
        q: "What program lengths do you offer?",
        a: "Every live program is available as a 30-Day Foundation, 90-Day Journey or 180-Day Commitment. Mukta will recommend the right length for you on your consultation call.",
      },
      {
        q: "How soon can I expect results?",
        a: "Every body is different, so we never promise a fixed timeline — some clients notice weight loss first, others notice inch loss or general health improvements before the scale moves. As a general pattern, it takes about 12 weeks for nutrition, movement and mindset to fully align, though most people start feeling better within the first 4 weeks.",
      },
      {
        q: "Do you work with specific ages or conditions that need a doctor?",
        a: "We work with adults 18 and older, across weight loss and hormone health concerns for women of all ages. If your reports show discrepancies beyond nutrition, we loop in a doctor — either one you already have, or one we can refer you to.",
      },
    ],
  },
  {
    name: "blog-section",
    pill: "Blog",
    title: "The latest insights on nutrition health",
    lead: "Stay informed with expert articles covering the latest nutrition research, healthy eating habits and holistic wellness tips.",
    posts: [
      {
        image: "/images/blog/blog1.jpg",
        date: "04",
        title: "Best foods to naturally boost daily energy levels",
        text: "The foods you eat play a key role in maintaining steady energy throughout the day.",
      },
      {
        image: "/images/blog/blog2.jpg",
        date: "04",
        title: "Explore science-backed habits for healthy eating",
        text: "The foods you eat play a key role in maintaining steady energy and better digestion.",
      },
      {
        image: "/images/blog/blog3.jpg",
        date: "04",
        title: "How personalized nutrition supports lasting wellness",
        text: "The foods you eat play a key role in maintaining steady energy and lifestyle balance.",
      },
    ],
  },
  {
    name: "testimonials-section",
    pill: "Testimonials",
    title: "Real client stories",
    lead: "Real results from people who transformed their relationship with food — without crash diets.",
    items: [
      {
        image: "/images/testimonials/royal.png",
        name: "Royal Ryder",
        role: "Client",
        quote:
          "Eat Rrite is the best nutrition and wellness experience I have come across in the pursuit of good health and weight loss. Mukta has a highly constructive, holistic approach and deep knowledge of her subject.",
      },
      {
        image: "/images/testimonials/snehi.jpg",
        name: "Snehi Singh",
        role: "Client",
        quote:
          "Mukta is my go-to person for any kind of diet advice. She has been extremely helpful not just for my health through yoga and diet, but also during chronic gastric ailments for my family.",
      },
      {
        image: "/images/testimonials/aakarsh.jpg",
        name: "Kamal Aakarsh Vishnubhotla",
        role: "Client",
        quote:
          "I lost 12kg in 4 months and the best part is that the practices Mukta shared stayed on as habits. I never really went back to gaining the weight afterwards.",
      },
    ],
  },
  {
    name: "cta-section",
    pill: "Start Your Journey",
    title: "Come Experience the Magic of Eat Rrite.",
    text: "Don't just chase weight loss — bring in holistic wellbeing.",
    cta: { label: "Start Your Journey Now", href: "/appointment" },
  },
];
