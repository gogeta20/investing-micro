<script setup lang="ts">
</script>
<template>
  <Particles
    id="tsparticles"
    :particlesInit="particlesInit"
    :particlesLoaded="particlesLoaded"
    :options="{
      background: {
        color: {
          value: '#0c0c11',
        },
      },
      fpsLimit: 144,
      fullScreen: {
        enable: true,
        zIndex: -1,
      },

      interactivity: {
        detectsOn: 'window',
        events: {
          onClick: {
            enable: true,
            mode: 'push',
          },
          onHover: {
            enable: true,
            mode: 'grab',
          },
          resize: true,
        },
        modes: {
          bubble: {
            distance: 300,
            duration: 2,
            opacity: 0.8,
            size: 40,
          },
          push: {
            quantity: 4,
          },
          grab: {
            distance: 150,
            duration: 1,
          },
        },
      },
      particles: {
        color: {
          value: '#5a3e8e',
        },
        links: {
          color: '#5a3e8e',
          distance: 180,
          enable: true,
          opacity: 1,
          width: 1,
        },
        collisions: {
          enable: false,
        },
        move: {
          direction: 'left',
          enable: true,
          outMode: 'out',
          random: false,
          speed: 1,
          straight: false,
        },
        number: {
          density: {
            enable: true,
            value_area: 1000,
          },
          value: 60,
        },
        opacity: {
          value: 0.5,
        },
        shape: {
          type: 'circle',
        },
        size: {
          random: true,
          value: 3,
        },
      },
      detectRetina: true,
    }"
  />
</template>
<style></style>
