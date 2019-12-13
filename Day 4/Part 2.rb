count = 0

147981.upto(691423).each do |num|
    digits = num.digits

    consecutive = digits.each_cons(2).all? { |a, b| a >= b }

    double = digits.chunk(&:itself).any? { |_, chunk| chunk.size == 2 }

    count += 1 if consecutive && double
end

puts "Answer: #{count}"
