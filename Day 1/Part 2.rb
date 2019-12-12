def fuelForMass(mass)
  (mass.to_i / 3).floor - 2
end

modules = File.open(__dir__ + '/input.csv').read.split(",")

requiredFuel = 0

modules.each { |mass|
    moduleFuel = fuelForMass(mass)
    requiredFuel += moduleFuel

    while moduleFuel >= 0
        moduleFuel = fuelForMass(moduleFuel);
        requiredFuel += [0, moduleFuel].max
    end
}

puts requiredFuel
