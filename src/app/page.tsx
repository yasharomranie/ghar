import { Scene01Hero } from "@/sections/Scene01Hero";
import { Scene02IntoDarkness } from "@/sections/Scene02IntoDarkness";
import { Scene03FirstWater } from "@/sections/Scene03FirstWater";
import { Scene04AquariumReveal } from "@/sections/Scene04AquariumReveal";
import { Scene05LivingWorld } from "@/sections/Scene05LivingWorld";
import { Scene06Species } from "@/sections/Scene06Species";
import { Scene07Geology } from "@/sections/Scene07Geology";
import { Scene08StoneWaterLife } from "@/sections/Scene08StoneWaterLife";
import { Scene09Visit } from "@/sections/Scene09Visit";
import { Footer } from "@/components/Footer";

export default function Home() {
  return (
    <>
      <Scene01Hero />
      <Scene02IntoDarkness />
      <Scene03FirstWater />
      <Scene04AquariumReveal />
      <Scene05LivingWorld />
      <Scene06Species />
      <Scene07Geology />
      <Scene08StoneWaterLife />
      <Scene09Visit />
      <Footer />
    </>
  );
}
