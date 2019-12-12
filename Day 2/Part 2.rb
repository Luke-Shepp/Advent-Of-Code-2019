input = File.open(__dir__ + '/input.csv').read

target = 19690720

answer = catch :finish do
    100.times do |noun|
        100.times do |verb|
            opcodes = input.split(",").map(&:to_i)
            opcodes[1] = noun
            opcodes[2] = verb

            opcodes.each_slice(4).with_index do |slice, index|
                opcode = slice[0]

                break if opcode == 99

                case opcode
                when 1
                    opcodes[slice[3]] = opcodes[slice[1]] + opcodes[slice[2]]
                when 2
                    opcodes[slice[3]] = opcodes[slice[1]] * opcodes[slice[2]]
                end

            end

            throw :finish, (100 * noun + verb) if opcodes[0] == target
        end
    end
end

puts "Answer: #{answer}"
