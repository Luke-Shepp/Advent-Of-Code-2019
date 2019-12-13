count = 0

147981.upto(691423).each do |num|
    digits = num.digits

    consecutive = digits.each_cons(2).all? { |a, b| a >= b }

    adjacent = digits.each_cons(2).any? { |a, b| a == b }

    count += 1 if consecutive && adjacent
end

puts "Answer: #{count}"
