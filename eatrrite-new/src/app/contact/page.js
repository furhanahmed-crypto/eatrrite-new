import { SiteShell } from "@/shared/components/SiteShell";
import { PageBanner } from "@/shared/components/PageBanner";
import { ContactPanel } from "@/components/contact/ContactPanel";
import { contactContent } from "@/constants/contact/contactContent";

export const metadata = {
  title: "Contact Eat Rrite",
  description:
    "Get in touch with Eat Rrite to book your nutrition consultation.",
};

export default function ContactPage() {
  return (
    <SiteShell current="contact">
      <PageBanner title="Contact Us" />
      <ContactPanel data={contactContent[0]} />
    </SiteShell>
  );
}
