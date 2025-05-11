<template>
  <div class="inventory">
      <h3 class="inventory-title">Inventaire</h3>
      <div v-if="!items || items.length === 0" class="empty-inventory">
          <p>Votre sac est vide</p>
      </div>
      <div v-else class="inventory-items">
          <div v-for="item in items" :key="item.id" class="inventory-item">
              <div class="item-content">
                  <h4 class="item-name">{{ item.name }}</h4>
                  <p class="item-description">{{ item.description }}</p>
              </div>
          </div>
      </div>
  </div>
</template>

<script setup>
defineProps({
  items: {
      type: Array,
      default: () => []
  }
});
</script>

<style scoped>
.inventory {
  background-color: #222;
  border-radius: 8px;
  padding: 12px;
  height: 70%;
  border: 1px solid #444;
  overflow-y: auto;
  max-height: calc(100vh - 70px);
}

.inventory-title {
  margin-top: 0;
  margin-bottom: 12px;
  font-size: 1.2rem;
  color: #e0e0e0;
  border-bottom: 1px solid #444;
  padding-bottom: 8px;
  position: sticky;
  top: 0;
  background-color: #222;
  z-index: 1;
}

.empty-inventory {
  color: #888;
  font-style: italic;
  text-align: center;
  padding: 10px 0;
}

.inventory-items {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.inventory-item {
  display: flex;
  align-items: center;
  background-color: #333;
  border-radius: 4px;
  padding: 8px;
  transition: transform 0.2s;
}

.inventory-item:hover {
  transform: translateX(-5px);
  background-color: #3a3a3a;
}

.item-content {
  flex-grow: 1;
}

.item-name {
  margin: 0 0 4px 0;
  font-size: 1rem;
  color: #e0e0e0;
}

.item-description {
  margin: 0;
  font-size: 0.9rem;
  color: #aaa;
}

.inventory::-webkit-scrollbar {
  width: 8px;
}

.inventory::-webkit-scrollbar-track {
  background: #1a1a1a;
  border-radius: 4px;
}

.inventory::-webkit-scrollbar-thumb {
  background: #444;
  border-radius: 4px;
}

.inventory::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>