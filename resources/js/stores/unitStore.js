import { defineStore } from 'pinia'

export const useUnitStore = defineStore('units', {
  state: () => ({
    weightUnit: localStorage.getItem('weightUnit') || 'kg',
    heightUnit: localStorage.getItem('heightUnit') || 'cm',
  }),
  getters: {
    weightLabel: (state) => state.weightUnit,
    heightLabel: (state) => state.heightUnit,
  },
  actions: {
    setUnits(weightUnit, heightUnit) {
      this.weightUnit = weightUnit
      this.heightUnit = heightUnit
      localStorage.setItem('weightUnit', weightUnit)
      localStorage.setItem('heightUnit', heightUnit)
    },
    // Convert kg (stored) → display unit
    convertWeight(kg) {
      if (kg == null) return null
      const v = this.weightUnit === 'lb' ? kg * 2.20462 : kg
      return parseFloat(v.toFixed(1))
    },
    // Convert display unit → kg (for API)
    toKg(value) {
      if (value == null) return null
      return this.weightUnit === 'lb' ? parseFloat((value / 2.20462).toFixed(2)) : value
    },
    // Convert cm (stored) → display unit
    convertHeight(cm) {
      if (cm == null) return null
      const v = this.heightUnit === 'in' ? cm / 2.54 : cm
      return parseFloat(v.toFixed(1))
    },
    // Convert display unit → cm (for API)
    toCm(value) {
      if (value == null) return null
      return this.heightUnit === 'in' ? parseFloat((value * 2.54).toFixed(1)) : value
    },
  },
})
