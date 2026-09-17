export const programs = [
  {
    slug: "weight-lifestyle-management",
    name: "Nutrition for Weight & Lifestyle Management",
    short: "Weight & Lifestyle Management",
    image: "/images/services/weight.jpg",
    imageSecondary: "/images/hero/healthy-plate.jpg",
    summary:
      "Lose weight without giving up the food you grew up eating — dal-chawal, idli-dosa-sambar and paratha-sabzi included.",
    about:
      "This is Eat Rrite's core weight-loss program — built for anyone who wants to lose weight without giving up the food they grew up eating. Instead of a generic calorie chart, Mukta builds your plan around your actual reports, your daily routine, and your favourite meals.",
    benefits: [
      "Visible, honest changes in weight and body composition",
      "Keep eating your everyday meals",
      "A plan built on your body's reports",
      "Direct WhatsApp access to Mukta",
      "A sustainable shift in habits",
    ],
    ideal:
      "Anyone looking to lose weight — without crash diets, starvation, or giving up the food they grew up eating.",
    mukta:
      "Led personally by Mukta Patil. Every weight-loss client works directly with her, shaped by 8 years of hands-on experience.",
    expect:
      "Every body responds differently. As a general pattern, someone starting at around 40% body fat typically drops to the 25–30% range with 90 days of consistency.",
  },
  {
    slug: "diabetes-management",
    name: "Nutrition for Diabetes Management & Reversal",
    short: "Diabetes Management & Reversal",
    image: "/images/services/diabetes.jpg",
    imageSecondary: "/images/hero/salad.jpg",
    summary:
      "Manage and work toward reversing diabetes or high blood sugar with food you already eat — alongside your doctor.",
    about:
      "For anyone diagnosed with diabetes or high blood sugar, this program looks at nutrition as a genuine management and reversal tool — built around your reports and regular meals.",
    benefits: [
      "Blood sugar changes from the first 4 weeks",
      "Path toward long-term stabilisation",
      "Coordination with your doctor",
      "Meals from regular Indian food",
      "Direct WhatsApp access to Mukta",
    ],
    ideal: "Anyone diagnosed with diabetes or high blood sugar.",
    mukta:
      "Led personally by Mukta Patil using traditional Indian food wisdom and evidence-based nutrition.",
    expect:
      "Most clients notice changes within 4 weeks. By 90–180 days, blood sugar tends to stabilise.",
  },
  {
    slug: "gut-health",
    name: "Nutrition for Gut Health",
    short: "Gut Health",
    image: "/images/services/gut.jpg",
    imageSecondary: "/images/about/bowl.jpg",
    summary:
      "Relief from acidity, bloating, constipation and GERD — using your regular Indian meals.",
    about:
      "Mukta works with your regular meals, adjusting how and what you combine, so gut issues are addressed without replacing your diet.",
    benefits: [
      "Relief from bloating, acidity, constipation and GERD",
      "Goal of coming off antacids",
      "Plan around home-cooked meals",
      "Direct access for flare-ups",
      "Weekly structure with flexibility",
    ],
    ideal:
      "Anyone dealing with acidity, bloating, constipation, GERD, or gut-related anxiety.",
    mukta:
      "Led personally by Mukta Patil — gut health is an area clients often say she truly understands.",
    expect:
      "The goal is complete resolution of gut-related issues — including no longer needing antacids.",
  },
  {
    slug: "female-hormone-health",
    name: "Female Hormone Health Diet Program",
    short: "Female Hormone Health",
    image: "/images/services/hormone.jpg",
    imageSecondary: "/images/about/kitchen.jpg",
    summary:
      "Nutrition for PCOS, PCOD, perimenopause, menopause and postpartum recovery.",
    about:
      "Mukta builds your plan around your specific hormonal picture and reports, addressing the root cause rather than only the symptoms.",
    benefits: [
      "More regular cycles and better PMS control",
      "Better insulin sensitivity and mood",
      "Weight addressed with hormonal root cause",
      "Adapts across life stages",
      "Direct WhatsApp access through flare-ups",
    ],
    ideal:
      "Women managing PCOS, PCOD, PMS, perimenopause, menopause, or postpartum recovery.",
    mukta:
      "Eat Rrite was born from Mukta's own postpartum health crisis — she leads this program personally.",
    expect:
      "Clients typically report weight and fat loss, better mood, more regular cycles, and reduced cortisol-related belly fat.",
  },
];

export function findProgram(slug) {
  return programs.find((item) => item.slug === slug) || null;
}
