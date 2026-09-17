import { siteConfig } from "@/config/site";
import { programs } from "@/constants/programs/programs";

export const contactContent = [
  {
    name: "contact-section",
    title: "Let's Start With a Conversation",
    text: "Eat Rrite works with clients entirely online, so wherever you're based, you can book a consultation with Mukta directly.",
    programs: programs.map((item) => item.short),
    phone: siteConfig.phone,
    phoneHref: siteConfig.phoneHref,
    email: siteConfig.email,
    emailHref: siteConfig.emailHref,
    address1: siteConfig.address1,
    address2: siteConfig.address2,
    locationsNote: siteConfig.locationsNote,
    hours: siteConfig.hours,
    whatsapp: siteConfig.whatsapp,
  },
];
