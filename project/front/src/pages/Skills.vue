<script setup lang="ts">
const desktopSkills = [
  { label: "PHP", height: "200px", color: "bg-white" },
  { label: "JS", height: "200px", color: "bg-white" },
  { label: "PYTHON", height: "170px", color: "bg-white" },
  { label: "JAVA", height: "180px", color: "bg-white" },
  { label: "RUST", height: "100px", color: "bg-white" },
  { label: "SQL", height: "200px", color: "bg-white" },
  { label: "Symfony", height: "200px", color: "cheapest" },
  { label: "Vue", height: "180px", color: "cheapest" },
  { label: "Django", height: "120px", color: "cheapest" },
  { label: "SpringBoot", height: "130px", color: "cheapest" },
  { label: "Docker", height: "180px", color: "cheapest" },
  { label: "Git", height: "190px", color: "cheapest" },
];

const mobileSkills = desktopSkills.map((skill) => ({
  label: skill.label,
  width: skill.height,
  color: skill.color,
}));
</script>
<script setup lang="ts"></script>
<template>
  <div class="content bg-green">
    <div>
      <p class="kode-style text-4xl md:text-6xl text-center text-white mb-4">Skills</p>
      <div class="container-tech-experience">
        <!-- Escritorio -->
        <div class="skills hidden sm:flex">
          <ul>
            <li v-for="(skill, index) in desktopSkills" :key="index" class="tech">
              <span class="experience" :class="skill.color" :style="{ height: skill.height }"></span>
              <span class="ttl1">{{ skill.label }}</span>
            </li>
          </ul>
        </div>

        <!-- Móvil -->
        <div class="skills-movil sm:hidden">
          <ul>
            <li v-for="(skill, index) in mobileSkills" :key="index" class="tech-mobile">
              <span class="ttl1">{{ skill.label }}</span>
              <span class="experience" :class="skill.color" :style="{ width: skill.width }"></span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>


<style scoped>
.skills-movil{
  display: flex;
  flex-direction: column;
  gap: .5rem
}
.tech-mobile{
  display: grid;
  grid-template-columns: 1fr 2fr;
	margin: 0 4px;
}

.tech-mobile span {
  margin: 5px;
}

 .tech-mobile span.experience {
	min-height: 20px;
	background-color: #FFF ;
	transform-origin: 100% 100%;
	border-radius: 3px;
}

 .tech-mobile span.experience.cheapest {
	background-color: #EF3D84 ;
}

 .tech-mobile span.experience:hover {
	background-color: #ccc ;
	cursor: pointer;
}

 .tech-mobile span.experience.cheapest:hover {
	background-color: #C13773 ;
	cursor: pointer;
}

.content{
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.ttl1,.ttl2{
	color: #fff !important;
}

.container-tech-experience {
	position: relative;
  min-height: 70vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.skills {
  display: flex;
  align-items: center;
}


 .tech {
	margin: 0 4px;
	display: inline-block;
	width: 88px;
	text-align: center;
}

 .tech a
,  .tech span {
	display: block;
}

 .tech span:first-of-type {
	margin-bottom: 7px;
}

 .tech span:last-of-type {
	margin: 10px 0 7px;
}

 .tech button.btn2 {
	width: 60px;
	padding-left: 0;
	padding-right: 0;
}

 .tech span.experience {
	min-height: 20px;
	background-color: #FFF ;
	transform-origin: 100% 100%;
	border-radius: 3px;
}

 .tech span.experience.cheapest {
	background-color: #EF3D84 ;
}

 .tech span.experience:hover {
	background-color: #ccc ;
	cursor: pointer;
}

 .tech span.experience.cheapest:hover {
	background-color: #C13773 ;
	cursor: pointer;
}

@media (max-width: 640px) {
  .tech{
    display: none;
  }
  .container-tech-experience {
    flex-direction: row;
  }
  .tech {
	  width: 28px;
  }
}

@media (min-width: 640px) {
  .skills-movil {
    display: none;
  }
}
</style>
