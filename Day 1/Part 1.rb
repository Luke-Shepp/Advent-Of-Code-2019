def fuelForMass(mass)
  (mass.to_i / 3).floor - 2
end

modules = File.open(__dir__ + '/input.csv').read.split(",")

fuel = 0

modules.each { |mass|
  fuel += fuelForMass(mass)
}

puts fuel
