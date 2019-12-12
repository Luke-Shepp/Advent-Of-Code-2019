opcodes = File.open(__dir__ + '/input.csv').read.split(",").map(&:to_i)

opcodes.each_slice(4).with_index { |slice, index|
    opcode = slice[0]

    break if opcode == 99

    case opcode
    when 1
        opcodes[slice[3]] = opcodes[slice[1]] + opcodes[slice[2]]
    when 2
        opcodes[slice[3]] = opcodes[slice[1]] * opcodes[slice[2]]
    end
}

puts "Answer: #{opcodes[0].to_s}"
